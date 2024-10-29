<script setup>
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
const route = useRoute()

const operations  = ref([])
const categories = ref([])
const types = ref([])
const total = ref(0)

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
  console.log(p)
  try{
    const response = await axios.get('/api/operations/', {params: {...filters.value}})
    operations.value = response.data.operations
    categories.value = response.data.categories
    types.value = response.data.types
    total.value = response.data.total
  }catch (e) {
    console.log(e)
  }
}

const columns = [
  { name: 'amount', label: 'Сумма', field: 'amount', align:'left'},
  { name: 'date', label: 'Дата', field: 'date', align:'left'},
  { name: 'description', label: 'Описание', field: 'description', align:'left'},
  { name: 'categories', label: 'Категория', field: 'categories', align:'left'},
  { name: 'types', label: 'Тип', field: 'types', align:'left'},
  { name: 'is_completed', label: 'Статус', field: 'is_completed', align:'left'},
];

const updatePagination = (newPagination) => {
  pagination.value = newPagination;
  applyFilters();
};

const applyFilters = () => {
  const sanitizedFilters = JSON.parse(JSON.stringify(filters.value));
  refresh(sanitizedFilters)
};

onMounted(() => {
  if (route.name == 'Operations')
    refresh()
})
</script>
<template>
  <q-page v-if="operations">
    <q-form @submit.prevent="applyFilters" class="items-center q-pa-md bg-grey-4">
      <div class="row justify-between">
        <q-input class="col-2" dense outlined filled v-model="filters.dateFrom" label="Дата начала" type="date" />
        <q-input class="col-2" dense outlined filled v-model="filters.dateTo" label="Дата окончания" type="date" />
        <q-select
            class="col-3"
            dense
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
            outlined
            filled
            v-model="filters.category"
            :options="categories"
            label="Фильтр по категориям"
            option-value="id"
            option-label="name"
        />
      </div>
      <div class="row justify-end q-mt-md">
        <q-btn class="text-right" dense size="sm" type="submit" label="Применить фильтры" color="primary" />
      </div>
      <div class="text-red text-h6">
        Общая сумма: {{total}}
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
    />
  </q-page>
</template>

<style scoped>
</style>
