<script setup>
import {onMounted, ref} from 'vue'
import {useRoute} from "vue-router";
import {Loading, Notify} from "quasar";

const route = useRoute()
const ruleId = route.params.id

const categories = ref([])
const isCategoryLoading = ref(false)

const selectedCategory = ref(null)
const selectedContractors = ref([])
const contractors = ref([])
const isContractorLoading = ref(false)

const operationsDialog = ref(false)
const operations = ref([])

const operationsHeaders = ref([
  {
    name: 'sum',
    label: 'Сумма',
    field: 'sber_amountRub',
  },
  {
    name: 'date',
    label: 'Дата',
    field: 'date_at',
  },
  {
    name: 'payee_contractor',
    label: 'Контрагент',
    field: 'payee_contractor',
  },
  {
    name: 'purpose',
    label: 'Назначение платежа',
    field: 'sber_paymentPurpose'
  }
])

const rule = ref({
  name: '',
  purpose_expression: '',
})

const operationTypes = [
  {label: 'DEBIT', value: 'DEBIT'},
  {label: 'CREDIT', value: 'CREDIT'},
]

const refresh = async () => {
  try {
    const response = await axios.get(`/api/rules/${ruleId}`, {
      params: {
        include: 'category,contractor'
      }
    })
    rule.value = response.data.data
    selectedCategory.value = rule.value.category
    selectedContractors.value = rule.value.contractor
  } catch (e) {
    Notify.create({
      message: 'Ошибка получения данных',
      color: 'red',
      timeout: 2000
    })
  }
}

const getOperationsByRule = async () => {
  Loading.show({
    message: 'Загрузка...'
  });
  try {
    const response = await axios.get('/api/rules/operations-by-rule', {
      params: {
        rule: rule.value
      }
    })
    operations.value = response.data
    operationsDialog.value = true
  } catch (e) {
    Notify.create({
      message: 'Ошибка получения данных',
      color: 'red',
      timeout: 2000
    })
  } finally {
    Loading.hide()
  }
}

const validateOperations = async () => {
  Notify.create({
    message: 'Обработка началась',
    timeout: 2000
  })
  try {
    const response = await axios.get('/api/rules/validate-by-rule', {
      params: {
        rule: rule.value
      }
    })
    operations.value = response.data
    operationsDialog.value = true
    Notify.create({
      message: 'Обработка закончена',
      color: 'green',
      timeout: 2000
    })
  } catch (e) {
    Notify.create({
      message: 'Ошибка при валидации',
      color: 'red',
      timeout: 2000
    })
  }
}

onMounted(async () => {
  await refresh()
})

const submitForm = async () => {
  try {
    const response = await axios.put(`/api/rules/${ruleId}`, {
      category_id: selectedCategory.value.id,
      operation_type: rule.value.operation_type,
      purpose_expression: rule.value.purpose_expression
    })
    if (response?.data?.success) {
      Notify.create({
        message: 'Данные успешно сохранены',
        color: 'green',
        timeout: 2000
      })
    } else {
      Notify.create({
        message: 'Ошибка сохранения данных',
        color: 'red',
        timeout: 2000
      })
    }
  } catch (e) {
    Notify.create({
      message: 'Ошибка сохранения данных',
      color: 'red',
      timeout: 2000
    })
  }
}

const onCategorySelectChange = async (val, update, abort) => {
  if (val?.length > 4) {
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
    <q-dialog v-model="operationsDialog" maximized>
      <q-card>
       <div class="row justify-end">
         <q-btn @click="operationsDialog = false" flat round icon="close"></q-btn>
       </div>
        <q-table
            title="Найденные операции соответствующие правилу"
            :rows="operations"
            :columns="operationsHeaders"
            row-key="id"
            :rows-per-page-options="[20, 50]"
        >
          <template v-slot:body-cell-payee_contractor="props">
            <q-td :props="props">
              <div>
                {{ props.row.payee_contractor?.full_name || 'Нет данных' }}
              </div>
            </q-td>
          </template>
        </q-table>
      </q-card>
    </q-dialog>
    <div class="row q-mx-auto bg-white col-10 col-sm-8">
      <q-card class="bg-white q-px-xl blue col-12">
        <q-btn class="q-my-md" icon="arrow_back" to="/operations/rules">Назад</q-btn>
        <div class="text-h4">
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
              label="Сохранить"
              type="submit"
              color="primary"
          />
          <q-btn
              class="q-ml-md q-mt-md"
              label="Показать соответствующие операции"
              @click="getOperationsByRule"
              color="primary"
          />
          <q-btn
              v-if="rule.id"
              class="q-ml-md q-mt-md"
              label="Провести привязку"
              @click="validateOperations"
              color="primary"
          />
        </q-form>
      </q-card>
    </div>
  </q-page>
</template>