<script setup>
import {onMounted, ref} from 'vue'
import {Notify, useQuasar} from 'quasar'
import axios from 'axios'
import {useRoute} from "vue-router";

const route = useRoute()
const categories = ref([])
const types = ref([])
const form = ref({
  amount: '',
  description: '',
  is_completed: false,
  date: '',
  category: null,
  type: null
})

const refresh = async () => {
  try {
    const response = await axios.get('/api/operations/create')
    categories.value = response.data.categories
    types.value = response.data.types
  } catch (error) {
    Notify.create({
      message: 'Ошибка получения данных',
      color: 'red'
    })
  }
}

const submitForm = async () => {
  try {
    const  response = await axios.post('/api/operations', form.value)
    if (response.data.success) {
      Notify.create({
        message: 'Операция успешно создана',
        color: 'positive'
      })
      form.value = { amount: '', descriptions: '', is_completed: false, date: '' }
    }else{
      Notify.create({
        message: 'Ошибка создания операции',
        color: 'red'
      })
    }
  } catch (error) {
    Notify.create({
      message: 'Ошибка отправки данных',
      color: 'red'
    })
  }
}

onMounted(() => {
  if (route.name == 'OperationCreate')
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
          <q-input
              class="q-mt-md"
              dense
              outlined
              v-model="form.amount"
              label="Сумма"
              type="number"
              required
          />
          <q-input
              class="q-mt-md"
              type="textarea"
              dense
              outlined
              v-model="form.description"
              label="Описание"
              required
          />
          <q-checkbox
              class="q-mt-md"
              dense
              outlined
              v-model="form.is_completed"
              label="Is Completed?"
          />
          <q-input
              class="q-mt-md"
              dense
              outlined
              v-model="form.date"
              label="Date"
              type="date"
              required
          />
          <q-select
              class="col-3 q-mt-md"
              dense
              clearable
              outlined
              filled
              v-model="form.type"
              :options="types"
              label="Фильтр по типам"
              option-value="id"
              option-label="name"

          />
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