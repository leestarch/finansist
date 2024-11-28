<script setup>
import {onMounted, ref} from 'vue'
import {Loading, Notify} from "quasar";
import {useRoute} from "vue-router";

const route = useRoute()
const categoryID = route.params.id
const categories = ref([])
const isCategoryLoading = ref(false)


const category = ref({
  name: '',
  parent: null,
})
const refresh = async () => {
  Loading.show();
  try{
    const response = await axios.get(`/api/categories/${categoryID}`, {
      params: {
        load: 'parent',
      }
    })
    category.value = response.data.data
    console.log(response.data.data)
  }catch (e) {
    console.log(e)
    Notify.create({
      message: 'Ошибка получения данных',
      color: 'red'
    })
  }
  Loading.hide()
}

const onCategorySelectChange = async (val, update, abort) => {
  if (val.length >= 2) {
    isCategoryLoading.value = true;
    try {
      const response = await axios.get('/api/categories', {
        params: {
          q: val
        }
      });
      categories.value = response.data.data;

      update(() => categories.value);
    } catch (e) {
      Notify.create({
        message: "Fetching categories failed",
        color: "red",
      });
    }
    isCategoryLoading.value = false;
  }
}
const submitForm = async () => {
  Loading.show();
  try {
    category.value.parent_id = category.value.parent?.id
    const response = await axios.put(`/api/categories/${categoryID}`, category.value)
    if(response?.data?.success) {
      Notify.create({
        message: 'Данные успешно сохранены',
        color: 'green',
        timeout: 2000
      })
      await refresh()
    }
  }catch (e) {
    console.log(e)
  }
  Loading.hide()
}

onMounted(() => {
  refresh()
})
</script>

<template>
  <q-page class="row shadow-3 bg-grey-2">
    <div class="row q-mx-auto bg-white col-10 col-sm-8" v-if="category">
      <q-card class="bg-white q-px-xl blue col-12">
        <div class="text-h4 q-mt-md">
          Редактирование категории
        </div>
        <q-form class="q-mt-xl" @submit.prevent="submitForm">
          <q-input
              label="Название"
              dense
              outlined
              v-model="category.name"
              class="q-mt-md"
          />

          <q-select
              class="col-3 q-mt-md"
              dense
              clearable
              outlined
              v-model="category.parent"
              :options="categories"
              label="Родительская категория"
              option-value="id"
              option-label="name"
              use-input
              input-debounce="300"
              hint="Введите не менее 3 символов"
              @filter="onCategorySelectChange"
              :loading="isCategoryLoading"
          />
          <q-btn
              type="submit"
              label="Сохранить"
              color="primary"
              class="q-mt-md"
          />
        </q-form>
      </q-card>
    </div>
  </q-page>
  
</template>

<style scoped>

</style>