<template>
  <q-page v-if="treeCategories.length">
    <q-spinner v-if="isFetching" color="primary" size="150px" class="q-my-md" />
    <q-form @submit.prevent="applyFilters" class="items-center q-pa-md bg-grey-4">
      <div class="row">
        <q-input class="col-2" clearable dense outlined filled v-model="filters.dateFrom" label="Дата начала" type="date" />
        <q-input class="col-2 q-ml-lg" dense clearable outlined filled v-model="filters.dateTo" label="Дата окончания" type="date" />
        <q-select
            class="col-3 q-ml-lg"
            dense outlined filled
            label="Группировать по"
            v-model="filters.groupBy"
            :options="[
                {label:'Дням', value:'daily'},
                {label:'Неделям', value:'weekly'},
                {label:'Месяцам', value: 'monthly'},
                {label:'Кварталам', value: 'quarterly'},
            ]"
        >
        </q-select>
      </div>
      <div class="row q-mt-md">
        <q-btn class="text-right" dense size="sm" type="submit" label="Применить фильтры" color="primary" />
      </div>
    </q-form>

    <TreeTable
        class="custom-tree-table q-mt-md q-px-sm"
        :value="treeCategories"
        tableStyle="min-width: 60rem; border-collapse: separate; border-spacing: 0 0.5rem "
    >
      <Column
          field="name"
          header="Категория"
          expander
          style="width: 20%; font-weight: bold;"
      ></Column>

      <!-- Render dynamic columns for each day -->
      <Column
          v-for="(date, index) in dateColumns"
          :key="index"
          :field="`date-${date}`"
          :header="date"
          style="min-width: 100px; text-align: right; padding-right: 1rem;"
      >
        <template #body="{ node }">
          <span
              :class="{
                  'text-red': node.data[`date-${date}`]?.toString().startsWith('-'),
                  'text-green': node.data[`date-${date}`] > 0,
                  '': node.data[`date-${date}`] === 0
                }"
          >
            {{ formatNumber(node.data[`date-${date}`]) }}
          </span>
        </template>
      </Column>
    </TreeTable>
  </q-page>
</template>

<script setup>
import TreeTable from 'primevue/treetable'
import Column from 'primevue/column'
import {onMounted, ref, watch} from 'vue'
import {Notify, Loading} from 'quasar'
import axios from 'axios'
import {
  eachDayOfInterval,
  eachWeekOfInterval,
  eachMonthOfInterval,
  eachQuarterOfInterval,
  format,
  parseISO,
  getYear, getISOWeek
} from 'date-fns';

const treeCategories = ref([])
const dateColumns = ref([])
const isFetching = ref(false)

const dateFormats = {
  daily: 'dd-MM-yyyy',
  weekly: 'W-oooo',
  monthly: 'MM-yyyy',
  quarterly: 'Q-yyyy'
};

function transformCategory(category) {
  return {
    key: category.id,
    data: {
      name: category.name,
      categoryType: category.category_type,
      ...Object.fromEntries(
          dateColumns.value.map(date => [
            `date-${date}`,
            category.daily_totals?.[date] ?? 0
          ])
      )
    },
    children: category.children
        ? category.children.map(child => transformCategory(child))
        : []
  }
}

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

const formatNumber = (value) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'decimal',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(value)
}



const filters = ref({
  groupBy: null,
  dateFrom: getCurrentMonthFirstDay(),
  dateTo: getCurrentMonthLastDay(),
  type: null,
  category: null
});

const applyFilters = () => {
  refresh()
}

onMounted(() => {
  refresh()
})
watch(() => filters.value.groupBy, () => {
  console.log("Group by changed to:", filters.value.groupBy);
  refresh();
});

const generateDateColumns = (startDate, endDate, groupBy) => {
  console.log("Generating date columns for:", groupBy);
  switch (groupBy) {
    case 'weekly':
      return eachWeekOfInterval({ start: startDate, end: endDate }, { weekStartsOn: 1 })  // Assuming Monday as week start
          .map(date => `${getISOWeek(date)}-${getYear(date)}`);
    case 'monthly':
      return eachMonthOfInterval({ start: startDate, end: endDate })
          .map(date => format(date, dateFormats.monthly));
    case 'quarterly':
      return eachQuarterOfInterval({ start: startDate, end: endDate })
          .map(date => format(date, dateFormats.quarterly));
    case 'daily':
    default:
      return eachDayOfInterval({ start: startDate, end: endDate })
          .map(date => format(date, dateFormats.daily));
  }
};

const refresh = async () => {
  Loading.show({
    message: 'Загрузка...'
  });
  try {
    const startDate = parseISO(filters.value.dateFrom);
    const endDate = parseISO(filters.value.dateTo);
    const groupBy = filters.value.groupBy?.value || 'daily';

    dateColumns.value = generateDateColumns(startDate, endDate, groupBy);

    const response = await axios.get('/api/categories', {
      params: {
        startDate: filters.value.dateFrom,
        endDate: filters.value.dateTo,
        groupBy: filters.value.groupBy?.value
      }
    });

    treeCategories.value = response.data.categories.map(category =>
        transformCategory(category)
    );
  } catch (e) {
    console.log(e);
    Notify.create({
      message: 'Fetching failed',
      color: 'red'
    });
  } finally {
    Loading.hide();
  }
};
</script>
<style>
</style>


<!--JQUERY START-->
<!--<template>-->
<!--  <table id="categoryTable" class="treetable">-->
<!--    <thead>-->
<!--      <tr>-->
<!--        <th>Category</th>-->
<!--        <th>Sep '24</th>-->
<!--        <th>Итого</th>-->
<!--        <th>Итого</th>-->
<!--      </tr>-->
<!--    </thead>-->
<!--    <tbody>-->
<!--    <tr-->
<!--        v-for="row in flatData"-->
<!--        :key="row.id"-->
<!--        :data-tt-id="row.id"-->
<!--        :data-tt-parent-id="row.parent_id"-->
<!--    >-->
<!--      <td>{{ row.name }}</td>-->
<!--      <td>{{ row.total_amount }}</td>-->
<!--      <td>{{ row.total_amount }}</td>-->
<!--      <td>{{ row.total_amount }}</td>-->
<!--    </tr>-->
<!--    </tbody>-->
<!--  </table>-->
<!--</template>-->

<!--<style>-->
<!--  @import "../components/jqueryPlugins/jquery.treetable.css";-->
<!--</style>-->

<!--<script setup>-->
<!--import { ref, onMounted } from 'vue';-->

<!--const categoryTree = ref([-->
<!--  {-->
<!--    id: 4,-->
<!--    name: "Активы",-->
<!--    total_amount: -3891,-->
<!--    parent_id: null,-->
<!--    children: [-->
<!--      {-->
<!--        id: 16,-->
<!--        name: "Внеоборотные активы",-->
<!--        total_amount: 10354,-->
<!--        parent_id: 4,-->
<!--        children: [-->
<!--          {-->
<!--            id: 38,-->
<!--            name: "Другие внеоборотные",-->
<!--            total_amount: -8750,-->
<!--            parent_id: 16,-->
<!--            children: [],-->
<!--          },-->
<!--          {-->
<!--            id: 100,-->
<!--            name: "Основные средства",-->
<!--            total_amount: 13007,-->
<!--            parent_id: 16,-->
<!--            children: [],-->
<!--          },-->
<!--        ],-->
<!--      },-->
<!--      {-->
<!--        id: 87,-->
<!--        name: "Оборотные активы",-->
<!--        total_amount: -14914,-->
<!--        parent_id: 4,-->
<!--        children: [],-->
<!--      },-->
<!--    ],-->
<!--  },-->
<!--]);-->

<!--const flattenTree = (nodes, parentId = null) => {-->
<!--  let result = [];-->
<!--  nodes.forEach((node) => {-->
<!--    result.push({-->
<!--      ...node,-->
<!--      parent_id: parentId,-->
<!--    });-->
<!--    if (node.children && node.children.length > 0) {-->
<!--      result = result.concat(flattenTree(node.children, node.id));-->
<!--    }-->
<!--  });-->
<!--  return result;-->
<!--};-->

<!--const flatData = flattenTree(categoryTree.value);-->

<!--onMounted(async () => {-->
<!--  await import('../components/jqueryPlugins/jquery.treetable.js');-->
<!--  $('#categoryTable').treetable({ expandable: true });-->
<!--});-->
<!--</script>-->
<!--JQUERY END-->



<!--ИЗ ФАЛАЙ КИРИЛЛА -->
<!--<script setup>-->
<!--import { onMounted, ref } from "vue";-->
<!--import { useRoute } from "vue-router";-->
<!--import { Notify } from "quasar";-->
<!--import axios from "axios";-->
<!--import AccessItem from "../components/AccessItem.vue"; // Ensure axios is imported-->

<!--const route = useRoute();-->
<!--const categoryTree = ref([]);-->
<!--const selectedCategories = ref([]);-->
<!--const filters = ref({});-->

<!--const refresh = async () => {-->
<!--  try {-->
<!--    const response = await axios.get("/api/categories");-->
<!--    categoryTree.value = formatCategoriesForTree(response.data.categories);-->
<!--  } catch (e) {-->
<!--    Notify.create({-->
<!--      message: "Fetching categories failed",-->
<!--      color: "red",-->
<!--    });-->
<!--  }-->
<!--};-->

<!--const onCategorySelect = (selectedCategory) => {-->
<!--  filters.value.category = selectedCategory;-->
<!--};-->

<!--const formatCategoriesForTree = (categories) => {-->
<!--  return categories.map((category) => ({-->
<!--    id: category.id,-->
<!--    name: category.name,-->
<!--    total_amount: category.total_amount || 0,-->
<!--    children: category.children ? formatCategoriesForTree(category.children) : [],-->
<!--  }));-->
<!--};-->

<!--onMounted(() => {-->
<!--  if (route.name == "Categories") refresh();-->
<!--});-->
<!--</script>-->

<!--<template>-->
<!--  <q-page padding>-->
<!--      <AccessItem-->
<!--          v-for="category in categoryTree"-->
<!--          :key="category.id"-->
<!--          :category="category"-->
<!--      />-->
<!--  </q-page>-->
<!--</template>-->
<!--КОНЕЦ ФАЙЛА КИРИЛЛА -->




<!--СТАРТОВЫЙ СКРИПТ -->
<!--<script setup>-->
<!--import {onMounted, ref} from "vue";-->
<!--import {useRoute} from "vue-router";-->
<!--import {Notify} from "quasar";-->
<!--const route = useRoute()-->

<!--const categoryTree = ref([])-->
<!--const selectedCategories = ref([])-->
<!--const filters = ref({})-->

<!--const refresh = async (p) => {-->
<!--  try{-->
<!--    const response = await axios.get('/api/categories')-->
<!--    categoryTree.value = formatCategoriesForTree(response.data.categories);-->
<!--  }catch (e) {-->
<!--    Notify.create({-->
<!--      message:'fetching failed',-->
<!--      color:'red'-->
<!--    })-->
<!--  }-->
<!--}-->
<!--const onCategorySelect = (selectedCategory) => {-->
<!--  filters.value.category = selectedCategory;-->
<!--};-->

<!--const formatCategoriesForTree = (categories) => {-->
<!--  return categories.map((category) => ({-->
<!--    id: category.id,-->
<!--    label: `${category.name} (Total: ${category.total_amount || ''})`,-->
<!--    totalAmount: category.total_amount || 0,-->
<!--    children: category.children ? formatCategoriesForTree(category.children) : [],-->
<!--  }));-->
<!--};-->

<!--onMounted(() => {-->
<!--  if (route.name == 'Categories')-->
<!--    refresh()-->
<!--})-->
<!--</script>-->
<!--<template>-->
<!--  <q-page padding>-->
<!--    <q-tree-->
<!--        class="col-4"-->
<!--        dense-->
<!--        :nodes="categoryTree"-->
<!--        node-key="id"-->
<!--        tick-strategy="leaf"-->
<!--    v-model="selectedCategories"-->
<!--    @update:model-value="onCategorySelect"-->
<!--    >-->
<!--    <template v-slot:default="{ node }">-->
<!--      <div>{{ node.label }}</div>-->
<!--    </template>-->
<!--    </q-tree>-->

<!--  </q-page>-->
<!--</template>-->

<!--<style scoped>-->
<!--</style>-->
<!--КОНЕЦ СТАРТОВОГО СКРИПТА -->
