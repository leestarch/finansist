<script setup>
import {onMounted, ref} from 'vue'
import {useRoute} from "vue-router";
import {Notify} from "quasar";

const route = useRoute()
const ruleId = route.params.id

const categories = ref([])
const isCategoryLoading = ref(false)

const selectedCategory = ref(null)

const rule = ref({
  name: '',
  purpose_expression: ''
})

const operationTypes = [
  {label: 'DEBIT', value: 'DEBIT'},
  {label: 'CREDIT', value: 'CREDIT'},
]

const refresh = async () => {
  try{
     const response = await axios.get(`/api/operations/rules/${ruleId}`, {
       params:{
          include: 'contractor,category'
       }
     })
    console.log(response.data.data)
     rule.value = response.data.data
     selectedCategory.value = rule.value.category
  }catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

onMounted(async () => {
  await refresh()
})

const submitForm = async () => {
  try {
    const response = await axios.put(`/api/operations/rules/${ruleId}`, rule.value)
    if(response?.data?.success) {
      Notify.create({
        message:'Данные успешно сохранены',
        color:'green',
        timeout: 2000
      })
    }else{
      Notify.create({
        message:'Ошибка сохранения данных',
        color:'red',
        timeout: 2000
      })
    }
  }catch (e) {
    Notify.create({
      message:'Ошибка сохранения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const onCategorySelectChange = async (val, update, abort) => {
  if (val.length > 4) {
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

</script>

<template>
  <q-page class="row shadow-3 bg-grey-2">
    <div class="row q-mx-auto bg-white col-10 col-sm-8">
      <q-card class="bg-white q-px-xl blue col-12">
        <div class="text-h4 q-mt-md">
          Редактирование правила
        </div>
        <q-form class="q-mt-xl" @submit.prevent="submitForm">
          <q-select
              class="col-3 q-mt-md"
              dense
              clearable
              outlined
              filled
              v-model="selectedCategory"
              :options="categories"
              label="Выберите категорию"
              option-value="id"
              option-label="name"
              use-input
              input-debounce="300"
              hint="Start typing to search"
              @filter="onCategorySelectChange"
              :loading="isCategoryLoading"
          />

          <q-input
              model-value="name"
              label="Название правила"
              class="col-3 q-mt-md"
              outlined
              dense
              v-model="rule.name"
              filled
          />

          <q-select
              class="col-3 q-mt-md"
              dense
              clearable
              outlined
              filled
              v-model="rule.operation_type"
              :options="operationTypes"
              label="Тип операции"
          />

          <q-input
              model-value="name"
              label="Регулярное выражение назначения"
              class="col-3 q-mt-md"
              outlined
              dense
              v-model="rule.purpose_expression"
              filled
          />

          <q-btn
              class="q-mt-md"
              label="Сохранить"
              type="submit"
              color="primary"
          />
        </q-form>
      </q-card>
    </div>
  </q-page>
</template>