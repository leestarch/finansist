<script setup>
import {ref, computed, onMounted} from "vue";
import {Notify} from "quasar";

const operations = ref([]);
const categories = ref([]);
const types = ref([]);
const getCurrentMonthFirstDay = () => {
  const date = new Date();
  date.setDate(1);
  return date.toISOString().split("T")[0];
};

const getCurrentMonthLastDay = () => {
  const date = new Date();
  date.setMonth(date.getMonth() + 1);
  date.setDate(0);
  return date.toISOString().split("T")[0];
};

const filters = ref({
  dateFrom: getCurrentMonthFirstDay(),
  dateTo: getCurrentMonthLastDay(),
  type: null,
  category: null
});

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

const refresh = async () => {
  try {
    const response = await axios.get("/api/operations/summary", { params: filters.value });
    operations.value = response.data.data;
    categories.value = response.data.categories;
    types.value = response.data.types;
    console.log(operations.value);

  } catch (e) {
    Notify.create({
      message: "Ошибка получения данных",
      color: "red",
      timeout: 2000,
    });
  }
};

onMounted(() => {
  refresh();
});

const columns = computed(() => {
  const dateColumns = [];
  const startDate = new Date(filters.value.dateFrom);
  const endDate = new Date(filters.value.dateTo);

  while (startDate <= endDate) {
    const formattedDate = startDate.toLocaleDateString("en-GB").replace(/\//g, "-");
    dateColumns.push({ name: formattedDate, label: formattedDate, field: formattedDate });
    startDate.setDate(startDate.getDate() + 1);
  }

  return [
    { name: "category", label: "Категория", field: "category", align: "left" },
    ...dateColumns,
    { name: "total", label: "Итог за период", field: "total" }
  ];
});
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
    </q-form>
    <q-table
        :rows="operations"
        :columns="columns"
        row-key="category"
        :rows-per-page-options="[0]"
    >
      <template v-slot:body-cell="props">
        <q-td
            :props="props"
        >
          <template class='' v-if="props.col.field === 'category'">
              {{ props.row.category }}
          </template>

          <template v-else-if="props.col.field === 'total'">
            <span
                :class="{
                  'text-red': props.row[props.col.field]?.toString().startsWith('-'),
                  'text-green': parseFloat(props.row[props.col.field]) > 0,
                  '': parseFloat(props.row[props.col.field]) === 0
                }"
            >
              {{ formatNumber(props.row.total) }}
            </span>
          </template>

          <template v-else-if="columns.some(col => col.name === props.col.field)">
            <span
                :class="{
                  'text-red': props.row[props.col.field]?.toString().startsWith('-'),
                  'text-green': parseFloat(props.row[props.col.field]) > 0,
                  '': parseFloat(props.row[props.col.field]) === 0
                }"
            >
              {{ formatNumber(props.row[props.col.field] || 0) }}
            </span>
          </template>

          <template v-else>
              {{ props.row[props.col.field] }}
          </template>
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<style scoped>
</style>
