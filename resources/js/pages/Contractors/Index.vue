<script setup>
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {Notify} from "quasar";
const route = useRoute()

const contractors  = ref([])
const isChecking = ref(false)
const pagination = ref({
  page: 1,
  rowsPerPage: 50,
  rowsNumber: 0
});

const filters = ref({
  name: '',
  inn: '',
});

const refresh = async (p) => {
  try{
    const response = await axios.get('/api/contractors', {
      params: {...filters.value, paginate: pagination.value.rowsPerPage, page: pagination.value.page
      }})
    contractors.value = response.data.data
    console.log(contractors.value)

  }catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const checkContractorsOperation = async (id) => {
  isChecking.value = true
  try {
   const response = await axios.get(`/api/contractors/${id}/check`)
    if (response.data.success) {
      Notify.create({
        message:response.data.message,
        color:'green',
        timeout: 2000
      })
    } else {
      Notify.create({
        message:'Ошибка проверки',
        color:'red',
        timeout: 2000
      })
    }
  }catch (e) {
    Notify.create({
      message:'Ошибка проверки',
      color:'red',
      timeout: 2000
    })
  }
  isChecking.value = false
}

const columns = [
  { name: 'full_name', label: 'Имя', field: 'full_name', align:'left'},
  { name: 'inn_kpp', label: 'ИНН', field: 'inn_kpp', align:'left'},
  { name: 'actions', label: 'Действия', align:'left'},
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
  if (route.name == 'ContractorIndex')
    refresh()
})
</script>
<template>
  <q-page v-if="contractors">
    <q-form @submit.prevent="applyFilters" class="items-center q-pa-md bg-grey-4">
      <div class="row">
        <q-input class="col-3" clearable dense outlined filled v-model="filters.name" label="Имя"/>
        <q-input class="col-3 q-ml-lg" dense clearable outlined filled v-model="filters.inn" label="ИНН"/>
      </div>
      <div class="row q-mt-md">
        <q-btn class="text-right" dense size="sm" type="submit" label="Применить фильтры" color="primary" />
      </div>
    </q-form>
    <q-table
        v-if="contractors.length"
        class="q-mt-md q-px-sm"
        :rows="contractors"
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
          <template v-if="item.col.name=='full_name'"> {{item.row.full_name}} </template>
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
  </q-page>
</template>

<style scoped>
</style>
