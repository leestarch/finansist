<script setup>

import {onMounted, ref} from 'vue'
import {Loading, Notify} from "quasar";

const categories = ref([])
const q = ref('')
const pagination = ref({
  page: 1,
  rowsPerPage: 30,
  rowsNumber: 0
});

const refresh = async (q) => {
  Loading.show();
    try{
     const response = await axios.get('/api/categories', {
       params: {
         q: q,
         load: 'parent,children',
         paginate: pagination.value.rowsPerPage, page: pagination.value.page
       }
     })
      categories.value = response.data.data
      pagination.value.rowsNumber = response.data.meta.total
    }catch (e) {
      console.log(e)
        Notify.create({
            message: 'Fetching categories failed',
            color: 'red'
        })
    }
    Loading.hide();
}

const deleteCategory = async (id) => {
  try {
    const response = await axios.delete(`/api/categories/${id}`)
    if(response?.data?.success) {
      Notify.create({
        message:'Правило успешно удалено',
        color:'green',
        timeout: 2000
      })
      await refresh()
    }
  }catch (e) {
    Notify.create({
      message:'Ошибка удаления',
      color:'red',
      timeout: 2000
    })
  }
}

const handlePaginationUpdate = async (page) => {
  pagination.value.page = page
  await refresh()
}


const categoriesColumns = [
    {name: 'name', label: 'Название', align: 'left'},
    {name: 'parent', label: 'Родитель', align: 'left'},
    {name: 'children', label: 'Дочерние', align: 'left'},
    {name: 'actions', label: 'Действие', align: 'left'},
]
const click = () => {
  console.log('click')
}
const search = async () => {
  await refresh(q.value)
}
onMounted(() => {
    refresh()
})

</script>
<template>
  <q-page class="bg-grey-2">
    <div class="bg-white q-ma-sm q-pa-sm">
      <div class="rounded-borders bg-white shadow-3 row items-center justify-between q-px-md">
        <div class="">
          <q-btn
              :to="{name: 'CategoriesCreate'}"
              size="md"
              color="primary"
              label="Добавить"
          />
        </div>
        <div class="row justify-between items-center">
          <q-input
              v-model="q"
              class="q-px-sm q-my-sm"
              clearable
              dense
              outlined
              label="Поиск"
          />
          <div>
            <q-btn
                @click="search"
                size="md"
                color="green"
                label="поиск"
            />
          </div>
        </div>
      </div>
      <div class="rounded-borders bg-white shadow-3">
        <q-table
            v-if="categories.length"
            class="q-mt-md q-mx-sm"
            :rows="categories"
            :columns="categoriesColumns"
            row-key="id"
            :pagination="{rowsPerPage: pagination.rowsPerPage}"
            hide-bottom
            flat
            :loading="!categories.length"
        >
          <template v-slot:body-cell="item">
            <q-td>
              <template v-if="item.col.name=='name'">
                {{item.row.name}}
              </template>
              <template v-if="item.col.name=='parent'">
                {{item.row.parent?.name}}
              </template>
              <template v-if="item.col.name=='children'">
                <div v-for="child in item.row?.children" :key="child.id">
                  {{child?.name}},
                </div>
              </template>
              <template v-if="item.col.name=='actions'">
                <router-link :to="{name: 'CategoriesEdit', params: {id: item.row?.id}}">
                  <q-btn
                      flat
                      size="sm"
                      color="primary"
                      dense
                      icon="edit"
                  />
                </router-link>
                <q-btn
                    flat
                    size="sm"
                    dense
                    color="red"
                    icon="delete"
                    @click="deleteCategory(item.row?.id)" />
              </template>
            </q-td>
          </template>
        </q-table>
        <div class="row bg-white justify-center q-pb-md rounded-borders">
          <q-pagination
              v-model="pagination.page"
              :max="Math.ceil(pagination.rowsNumber / pagination.rowsPerPage)"
              boundary-numbers
              boundary-links
              :max-pages="10"
              @update:model-value="handlePaginationUpdate"
              class="text-center q-mt-md"
          ></q-pagination>
        </div>

      </div>

    </div>
  </q-page>
</template>