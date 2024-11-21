<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {useRoute} from "vue-router";
import {Notify} from "quasar";
import OperationTable from "../../components/Operations/OperationTable.vue";
const route = useRoute()
const filterParams = route.query


const operations  = ref([])
const totalIncome = ref(0)
const totalExpense = ref(0)

const pagination = ref({
  page: 1,
  rowsPerPage: 50,
  rowsNumber: 0
});

const filters = ref({
  dateFrom: '',
  dateTo: '',
  categoryQuery: '',
  name: '',
  purpose_expression: '',
  sberDirection: null,
});

watch(filters, () => {
  pagination.value.page = 1
}, {deep: true})

const refresh = async (p) => {
  console.log(filters.value)
  try{
    const response = await axios.get('/api/operations/', {params: {...filters.value, page: pagination.value.page}})

    operations.value = response?.data?.data

    pagination.value.rowsNumber = response.data?.meta?.total
    pagination.value.page = response.data?.meta?.current_page
    pagination.value.totalPages = response.data?.meta?.last_page
  }catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const columns = [
  { name: 'categories', label: 'Категория', field: 'categories', align:'left'},
  { name: 'description', label: 'Описание', field: 'description', align:'left'},
  { name: 'types', label: 'Тип', field: 'types', align:'left'},
  { name: 'is_completed', label: 'Статус', field: 'is_completed', align:'left'},
  { name: 'date', label: 'Дата', field: 'date', align:'left'},
  { name: 'amount', label: 'Сумма', field: 'amount', align:'left'},
];

const updatePagination = (newPagination) => {
  pagination.value = newPagination;
  applyFilters();
};

const applyFilters = () => {
  const sanitizedFilters = JSON.parse(JSON.stringify(filters.value));
  refresh(sanitizedFilters)
};

const formatNumber = (value) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'decimal',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(value)
}

const formattedIncome = computed(() => formatNumber(totalIncome.value))
const formattedExpense = computed(() => formatNumber(totalExpense.value))
const formattedDifference = computed(() => formatNumber(totalIncome.value - Math.abs(totalExpense.value)))

onMounted(() => {
    filters.value = filterParams ? filterParams : filters.value
    refresh()
})
</script>
<template>
  <q-page v-if="operations">
    <q-form @submit.prevent="applyFilters" class="items-center q-pa-md bg-grey-4">
      <div class="row">
        <q-input class="col-2 q-px-sm q-mt-sm" clearable dense outlined filled v-model="filters.dateFrom" label="Дата начала" type="date" />
        <q-input class="col-2 q-px-sm q-mt-sm" dense clearable outlined filled v-model="filters.dateTo" label="Дата окончания" type="date" />
        <q-input
            class="col-3 q-px-sm q-mt-sm"
            clearable
            dense
            outlined
            filled
            v-model="filters.categoryQuery"
            label="Фильтр по категориям"
        />
        <q-input
            class="col-3 q-px-sm q-mt-sm"
            clearable
            dense
            outlined
            filled
            v-model="filters.name"
            label="Название"
        />

        <q-input
            class="col-3 q-px-sm q-mt-sm"
            clearable
            dense
            outlined
            filled
            v-model="filters.purpose_expression"
            label="Регулярное выражение"
        />

        <q-select
            class="col-3 q-px-sm q-mt-sm"
            clearable
            dense
            outlined
            filled
            v-model="filters.sberDirection"
            label="Тип операции"
            :options="[
                {label: 'DEBIT', value: 'DEBIT'},
                {label: 'CREDIT', value: 'CREDIT'},
              ]"
        />
      </div>
      <div class="row q-mt-md">
        <q-btn class="text-right" dense size="sm" type="submit" label="Применить фильтры" color="primary" />
      </div>
      <div class="row justify-between items-center q-mt-md">
        <div>
          <q-btn
              to="/operations/create"
              class="text-right"
              dense
              size="sm"
              label="Создать операцию"
              color="primary"
          />
        </div>
      </div>
    </q-form>
    <OperationTable
        :operations="operations"
        :pagination="pagination"
        @update:pagination="(newPagination) => { pagination = newPagination; refresh(); }"
        @refresh="refresh"
    />
  </q-page>
</template>

<style scoped>
</style>
