<script setup>
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
const route = useRoute()

const operations  = ref([])
const categories = ref([])
const types = ref([])

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
  }catch (e) {
    console.log(e)
  }
}

const columns = [
  { name: 'amount', label: 'Amount', field: 'amount', align:'left'},
  { name: 'date', label: 'Date', field: 'date', align:'left'},
  { name: 'description', label: 'Description', field: 'description', align:'left'},
  { name: 'categories', label: 'Categories', field: 'categories', align:'left'},
  { name: 'types', label: 'Types', field: 'types', align:'left'},
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
        <q-input class="col-2" dense outlined filled v-model="filters.dateFrom" label="Date From" type="date" />
        <q-input class="col-2" dense outlined filled v-model="filters.dateTo" label="Date To" type="date" />
        <q-select
            class="col-3"
            dense
            outlined
            filled
            v-model="filters.type"
            :options="types"
            label="Filter by Type"
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
            label="Filter by Category"
            option-value="id"
            option-label="name"
        />
      </div>
      <div class="row justify-end q-mt-md">
        <q-btn class="text-right" type="submit" label="Apply Filters" color="primary" />
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
