<script setup>
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {Notify} from "quasar";
const route = useRoute()

const categoryTree = ref([])
const selectedCategories = ref([])
const filters = ref({})

const refresh = async (p) => {
  try{
    const response = await axios.get('/api/categories')
    categoryTree.value = formatCategoriesForTree(response.data.categories);
  }catch (e) {
    Notify.create({
      message:'fetching failed',
      color:'red'
    })
  }
}
const onCategorySelect = (selectedCategory) => {
  filters.value.category = selectedCategory;
};

const formatCategoriesForTree = (categories) => {
  return categories.map((category) => ({
    id: category.id,
    label: `${category.name} (Total: ${category.total_amount || ''})`,
    totalAmount: category.total_amount || 0,
    children: category.children ? formatCategoriesForTree(category.children) : [],
  }));
};

onMounted(() => {
  if (route.name == 'Categories')
    refresh()
})
</script>
<template>
  <q-page padding>
    <q-tree
        class="col-4"
        dense
        :nodes="categoryTree"
        node-key="id"
        tick-strategy="leaf"
    v-model="selectedCategories"
    @update:model-value="onCategorySelect"
    >
    <template v-slot:default="{ node }">
      <div>{{ node.label }}</div>
    </template>
    </q-tree>

  </q-page>
</template>

<style scoped>
</style>
