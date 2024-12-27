<script setup>
import {onMounted, ref, watch} from "vue";
import {Notify} from "quasar";

const contractors  = ref([])
const isChecking = ref(false)
const pagination = ref({
  page: 1,
  rowsPerPage: 30,
  rowsNumber: 0
});

const filters = ref({
  name: '',
  inn: '',
});

watch(filters, () => {
  pagination.value.page = 1
}, {deep: true})

const refresh = async () => {
  try{
    const response = await axios.get('/api/contractors', {
      params: {...filters.value, paginate: pagination.value.rowsPerPage, page: pagination.value.page
      }})
    contractors.value = response.data.data
    pagination.value.rowsNumber = response.data?.meta?.total

  }catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const columns = [
  { name: 'full_name', label: 'Имя', field: 'full_name', align:'left'},
  { name: 'inn_kpp', label: 'ИНН', field: 'inn_kpp', align:'left'},
  // { name: 'actions', label: 'Действия', align:'left'},
];

const handlePaginationUpdate = async (page) => {
  pagination.value.page = page
  await refresh()
}

const applyFilters = async () => {
  await refresh()
};

onMounted(() => {
  refresh()
})
</script>
<template>
  <q-page v-if="contractors" class="bg-grey-2">
    <div class="bg-white q-ma-sm q-pa-sm">
      <q-form @submit.prevent="applyFilters" class="items-center q-pa-md bg-grey-2 rounded-borders">
        <div class="row">
          <q-input class="col-3" clearable dense outlined filled v-model="filters.name" label="Имя"/>
          <q-input class="col-3 q-ml-lg" dense clearable outlined filled v-model="filters.inn" label="ИНН"/>
        </div>
        <div class="row justify-end q-mt-md">
          <q-btn class="text-right" dense size="sm" type="submit" label="Применить фильтры" color="green" />
        </div>
      </q-form>
      <div class="rounded-borders bg-white shadow-3">
        <q-table
            v-if="contractors.length"
            class="q-mt-md q-mx-sm"
            :rows="contractors"
            :columns="columns"
            row-key="id"
            :pagination="{rowsPerPage: pagination.rowsPerPage}"
            hide-bottom
            flat
            :loading="!contractors.length"
        >
          <template v-slot:body-cell="item">
            <q-td
                :class="item.row.is_wrong?'bg-red-2':(item.row.is_validated?'bg-green-2':'')"
                :item="item"
            >
              <template v-if="item.col.name=='full_name'">
                <router-link :to="`/contractors/${item.row.id}`">
                  {{item.row.full_name}}
                </router-link>
              </template>
              <template v-if="item.col.name=='inn_kpp'"> {{item.row.inn_kpp}} </template>
              <template v-if="item.col.name=='actions'">
                <q-btn
                    dense
                    flat
                    color="primary"
                    label="Проверить"
                    @click="checkContractorsOperation(item.row.id)"
                    :loading="isChecking"
                />
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

<style scoped>
</style>
