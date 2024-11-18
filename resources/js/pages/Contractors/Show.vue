<script setup>
import {onMounted, ref, watch} from "vue";
import {useRoute} from "vue-router";
import {Notify} from "quasar";

const route = useRoute()
const contractorId = route.params.id
const contractor = ref({})
const operations = ref([])
const rules = ref([])

const refresh = async () => {
  await Promise.all([fetchContractor(), fetchOperations(), fetchRules()]);
}

const operationsColumns = [
  { name: 'sber_amountRub', label: 'Сумма', field: 'sber_amountRub', align:'left'},
  { name: 'date_at', label: 'Дата', field:'date_at', align:'left'},
  { name: 'sber_paymentPurpose', label: 'Назначение', field: 'sber_paymentPurpose', align:'left'},
];

const rulesColumns = [
  { name: 'name', label: 'Имя', field: 'name', align:'left'},
  { name: 'purpose_expression', label: 'Регулярное выражение', field: 'purpose_expression', align:'left'},
];


const fetchContractor = async () => {
  try {
    const response = await axios.get(`/api/contractors/${contractorId}`)
    contractor.value = response.data.data
    console.log(contractor.value)
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const fetchOperations = async () => {
  try {
    const response = await axios.get(`/api/operations`, {
      params: {payee_contractor_id: contractorId, paginate: operationPagination.value.rowsPerPage, page: operationPagination.value.page}
    })
    operations.value = response.data.data
    operationPagination.value.rowsNumber = response.data?.meta?.total
    console.log(operations.value)
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const fetchRules = async () => {
  try {
    const response = await axios.get(`/api/operations/rules`, {
      params: {contractor_id: contractorId}
    })
    rules.value = response.data.data
    console.log(rules.value)
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const operationPagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0
});

watch(operationPagination, fetchOperations, { deep: true });

onMounted(() => {
    refresh()
})
</script>
<template>
  <q-page>
    <div class="row justify-center q-mt-lg">
      <div class="col-10 text-center justify-center">
        <div class="row text-h6">
            <p>Контрагент: {{contractor.full_name}}</p>
        </div>
        <div class="row text-h6">
          <p v-if="contractor.inn">ИНН: {{ contractor.inn_kpp }}</p>
          <p v-else>ИНН отсутствует</p>
        </div>
      </div>
      <div class="justify-center col-12 q-px-md q-mt-lg">
        <div v-if="rules.length">
          <p class="text-h6 text-center">
            Правила
          </p>
          <q-table
              class="q-mt-md q-px-sm"
              :rows="rules"
              :columns="rulesColumns"
              :rows-per-page-options="[5, 10, 25, 50, 100]"
              row-key="id"
          >
          </q-table>
        </div>
        <div v-else>
          <p class="text-h6 text-center">
            Для этого контрагента правила не установлены
          </p>
        </div>
      </div>
      <div class="justify-center col-12 q-px-md q-mt-lg">
        <div v-if="operations.length">
          <p class="text-h6 text-center">
            Операции
          </p>
          <q-table
              class="q-mt-md q-px-sm"
              :rows="operations"
              :columns="operationsColumns"
              :pagination.sync="operationPagination"
              :rows-per-page-options="[5, 10, 25, 50, 100]"
              row-key="id"
          >
          </q-table>
        </div>
        <div v-else>
          <p class="text-h6 text-center">
            Для этого контрагента операции не найдены
          </p>
        </div>
      </div>
      </div>
<!--    </div>-->

  </q-page>
</template>

<style scoped>
</style>
