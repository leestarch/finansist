<script setup>
import {onMounted, ref} from 'vue'
import {useRoute} from "vue-router";

const route = useRoute()
const categories = ref([])


const refresh = async () => {

}

const submitForm = async () => {

}

onMounted(() => {
  if (route.name == 'CreateRule')
    refresh()
})
</script>

<template>
  <q-page class="row shadow-3 bg-grey-2">
    <div class="row q-mx-auto bg-white">
      <q-card class="bg-white q-px-xl blue">
        <div class="text-h4 q-mt-md">
          Создание новой операции
        </div>
        <q-form class="q-mt-xl" @submit.prevent="submitForm">

          <q-select
              class="col-3 q-mt-md"
              dense
              clearable
              outlined
              filled
              v-model="form.category"
              :options="categories"
              label="Фильтр по категориям"
              option-value="id"
              option-label="name"
              use-input
              input-debounce="300"
              hint="Start typing to search"
          />
          <q-btn
              class="q-mt-md"
              label="Submit"
              type="submit"
              color="primary"
          />
        </q-form>
      </q-card>
    </div>
  </q-page>
</template>