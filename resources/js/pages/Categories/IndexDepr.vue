<template>
  <q-page>
    <TreeTable
        v-if="treeCategories.length"
        class="custom-tree-table q-mt-md q-px-sm q-mb-xl"
        :value="treeCategories"
        tableStyle="min-width: 80rem; border-collapse: separate; border-spacing: 0 0.5rem"
    >
      <Column
          field="name"
          header="Категория"
          expander
          style="width: 34%; font-weight: bold;"
      ></Column>
      <Column
          field="totalAmount"
          header="Сумма"
          style="width: 33%; text-align: right; padding-right: 1rem;"
      ></Column>
    </TreeTable>
  </q-page>
</template>


<script setup>
import TreeTable from 'primevue/treetable'
import Column from 'primevue/column'
import {onMounted, ref} from 'vue'
import {Loading, Notify} from "quasar";

const treeCategories = ref([])

function transformCategory(category) {
  console.log(category)
  return {
    key: category.id,
    data: {
      name: category.name,
      categoryType: category.category_type,
      totalAmount: category.total_amount,
      createdAt: category.created_at,
    },
    children: category.children
        ? category.children.map((child) => transformCategory(child))
        : [],
  }
}

onMounted(() => {
    refresh()
})
const refresh = async (p) => {
  Loading.show({
    message: 'Загрузка...',
  })
  try{
    const response = await axios.get('/api/categories')
    treeCategories.value = response.data.categories.map((category) => transformCategory(category));
  }catch (e) {
    Notify.create({
      message:'fetching failed',
      color:'red'
    })
  }finally {
    Loading.hide()
  }
}
</script>


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
