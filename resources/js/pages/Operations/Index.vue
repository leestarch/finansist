<script setup>
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {Notify} from "quasar";
const route = useRoute()

const operations  = ref([])
const categories = ref([])
const types = ref([])
const totalIncome = ref(0)
const totalExpense = ref(0)

const pagination = ref({
  page: 3,
  rowsPerPage: 50,
  rowsNumber: 0
});

const filters = ref({
  dateFrom: '',
  dateTo: '',
  type: null,
  category: null
});


const refresh = async (p) => {
  try{
    const response = await axios.get('/api/operations/', {params: {...filters.value}})
    operations.value = response.data.operations
    categories.value = response.data.categories
    types.value = response.data.types
    totalIncome.value = response.data.totalIncome
    totalExpense.value = response.data.totalExpense
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
  if (route.name == 'OperationIndex')
    refresh()
})
</script>
<template>
  <q-page v-if="operations">
    <q-form @submit.prevent="applyFilters" class="items-center q-pa-md bg-grey-4">
      <div class="row justify-between">
        <q-input class="col-2" clearable dense outlined filled v-model="filters.dateFrom" label="Дата начала" type="date" />
        <q-input class="col-2" dense clearable outlined filled v-model="filters.dateTo" label="Дата окончания" type="date" />
        <q-select
            class="col-3"
            dense
            clearable
            outlined
            filled
            v-model="filters.type"
            :options="types"
            label="Фильтр по типам"
            option-value="id"
            option-label="name"

        />
        <q-select
            class="col-3"
            dense
            clearable
            outlined
            filled
            v-model="filters.category"
            :options="categories"
            label="Фильтр по категориям"
            option-value="id"
            option-label="name"
        />
      </div>
      <div class="row q-mt-md">
        <q-btn class="text-right" dense size="sm" type="submit" label="Применить фильтры" color="primary" />
      </div>
      <div class="row justify-between items-center q-mt-md">
        <div class="row">
          <div class="text-h6 text-green">
            Прибыль: {{formattedIncome}}
          </div>
          <div class="text-h6 text-red q-ml-lg">
            Расход: {{formattedExpense}}
          </div>
          <div class="text-h6 text-blue q-ml-lg">
            Разница: {{formattedDifference}}
          </div>
        </div>
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
    <q-table
        v-if="operations.length"
        class="q-mt-md q-px-sm"
        :rows="operations"
        :pagination="pagination"
        :columns="columns"
        :rows-per-page-options="[5, 10, 25, 50, 100]"
        row-key="id"
        @update:pagination="updatePagination"
    >
      <template v-slot:body-cell="item">
        <q-td
            :class="item.row.is_wrong?'bg-red-2':(item.row.is_validated?'bg-green-2':'')"
            :item="item"
        >
          <template v-if="item.col.name=='description'"> {{item.row.description}} </template>

          <template v-if="item.col.name=='types'"> {{item.row.types}} </template>

          <template v-if="item.col.name=='date'"> {{item.row.date}} </template>

          <template v-if="item.col.name === 'amount'">
            {{ formatNumber(item.row.amount) }}
          </template>

          <template v-if="item.col.name=='categories'">
            {{item.row.categories}}
          </template>
          <template v-if="item.col.name=='is_completed'">
            {{item.row.is_completed?'Выполнено':'Не выполнено'}}
          </template>
        </q-td>
      </template>

    </q-table>
  </q-page>
</template>

<style scoped>
</style>
