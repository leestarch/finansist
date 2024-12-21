<script setup>
import {ref} from 'vue'
import {Notify} from "quasar";

const categories = ref([])
const contractors = ref([])

const operationTypes = [
  {label: 'DEBIT', value: 'DEBIT'},
  {label: 'CREDIT', value: 'CREDIT'},
]

const isCategoryLoading = ref(false)
const isContractorLoading = ref(false)

const selectedContractors = ref([])
const selectedCategory = ref(null)
const selectedOperationType = ref(operationTypes[0])

const rule = ref({
  purpose_expression: '',
  operation_type: selectedOperationType?.value?.value,
})

const submitForm = async () => {
  rule.value.contractor_ids = selectedContractors.value.map((contractor) => contractor.id);
  rule.value.category_id = selectedCategory?.value?.id;

  try {
    const response = await axios.post('/api/rules', rule.value);
    if(response.data.success) {
      Notify.create({
        message: "Правило успешно создано",
        color: "green",
      });
    } else {
      Notify.create({
        message: "Правило не создано",
        color: "red",
      });
    }
  } catch (e) {
    Notify.create({
      message: "Ошибка создания правила",
      color: "red",
    });
  }
}

const onCategorySelectChange = async (val, update, abort) => {
  if (val.length > 3) {
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
        message: "Ошибка получения категорий",
        color: "red",
      });
    }
    isCategoryLoading.value = false;
  }
}

const onContractorSelectChange = async (val, update, abort) => {
  if (val.length > 3) {
    isContractorLoading.value = true;
    try {
      const response = await axios.get('/api/contractors', {
        params: {
          q: val
        }
      });
      contractors.value = response.data.data;
      update(() => categories.value);
    } catch (e) {
      Notify.create({
        message: "Ошибка получения контрагентов",
        color: "red",
      });
    }
  }
  isContractorLoading.value = false;
}
</script>
<template>
  <q-page class="row shadow-3 bg-grey-2">
    <div class="row q-mx-auto bg-white col-10 col-sm-8">
      <q-card class="bg-white q-px-xl blue col-12">
        <q-btn class="q-my-md" icon="arrow_back" to="/operations/rules">Назад</q-btn>
        <div class="text-h4 q-mt-md">
          Создание правила
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
              @filter="onCategorySelectChange"
              :loading="isCategoryLoading"
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

          <q-select
              class="col-3 q-mt-md"
              dense
              clearable
              outlined
              filled
              option-value="value"
              option-label="label"
              emit-value
              v-model="rule.operation_type"
              :options="operationTypes"
              label="Тип операции"
          />

          <q-select
              class="col-3 q-mt-md"
              dense
              clearable
              outlined
              filled
              v-model="selectedContractors"
              :options="contractors"
              label="Выберете контрагентов"
              option-value="id"
              option-label="name"
              use-input
              multiple

              input-debounce="300"
              @filter="onContractorSelectChange"
              :loading="isContractorLoading"
          />

          <q-btn
              class="q-mt-md"
              label="Создать"
              type="submit"
              color="primary"
          />
        </q-form>
      </q-card>
    </div>
  </q-page>
</template>