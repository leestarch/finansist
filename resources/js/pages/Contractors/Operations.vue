<script setup>
import {useRoute} from "vue-router";
import {Notify} from "quasar";
import {onMounted, ref, watch} from "vue";
import OperationTable from "../../components/Operations/OperationTable.vue";

const route = useRoute()
const contractorId = route.params.id
const operations = ref([])
const isChecking = ref(false)

const pagination = ref({
  page: 1,
  rowsPerPage: 20,
  rowsNumber: 0,
});

const filters = ref({
  purposeQuery: '',
  categoryQuery: '',
  dateAt: null,
  dateFrom: null,
  dateTo: null,
})

watch(filters, () => {
  pagination.value.page = 1
}, {deep: true})

const refresh = async () => {
  try {
    const response = await axios.get(`/api/operations`, {
      params: {
        payee_contractor_id: contractorId,
        paginate: pagination.value.rowsPerPage,
        page: pagination.value.page,
        dateAt: filters.value.dateAt,
        dateTo: filters.value.dateTo,
        dateFrom: filters.value.dateFrom,
        purposeQuery: filters.value.purposeQuery,
        categoryQuery: filters.value.categoryQuery,
      }
    })
    operations.value = response?.data?.data

    pagination.value.rowsNumber = response.data?.meta?.total
    pagination.value.page = response.data?.meta?.current_page
    pagination.value.totalPages = response.data?.meta?.last_page
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const checkContractorsOperation = async () => {
  isChecking.value = true
  try {
    const response = await axios.get(`/api/contractors/${contractorId}/check`)
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

onMounted(() => {
  refresh()
})

</script>

<template>
  <q-page class="bg-grey-3">
    <div class="row justify-center">
      <div class="row justify-center col-12 q-px-md q-pt-lg q-mb-sm">
        <div class="col-3 bg-accent bg-white shadow-24 q-pa-sm rounded-borders mb">
          <div class="row">
            <span class="text-h6 text-center ">
              Действия
            </span>
            <span class="text-body2 q-mt-md">
              Проверить все операции контрагента:
              <q-btn
                  class="q-ml-sm"
                  dense
                  size="sm"
                  @click="checkContractorsOperation"
                  label="проверить"
                  color="primary"
              />
            </span>
            <span class="text-body2 q-mt-md">
              Создать новую операцию:
              <q-btn
                  class="q-ml-sm"
                  dense
                  size="sm"
                  :to="{name: 'OperationCreate', params: {contractorId: contractorId}}"
                  label="создать"
                  color="primary"
              />
            </span>
          </div>
        </div>
        <div class="col-9 bg-grey-3 q-px-sm ">
          <div class="bg-white shadow-3 q-px-sm rounded-borders q-pb-md">
            <p class="text-h6 text-center">
              Операции контрагента
            </p>
            <div class="row">
              <q-input
                  class="col-3 q-px-sm"
                  clearable
                  dense
                  outlined
                  filled
                  v-model="filters.dateAt"
                  label="Дата"
                  type="date"
              />

              <q-input
                  class="col-3 q-px-sm"
                  clearable
                  dense
                  outlined
                  filled
                  v-model="filters.categoryQuery"
                  label="Категории"
              />

              <q-input
                  class="col-3 q-px-sm"
                  clearable
                  dense
                  outlined
                  filled
                  v-model="filters.purposeQuery"
                  label="Назначение"
              />

            </div>
            <div class="row q-mt-sm">
              <q-input
                  class="col-2 q-px-sm"
                  clearable
                  dense
                  outlined
                  filled
                  v-model="filters.dateFrom"
                  label="Дата начала"
                  type="date"
              />
              <q-input
                  class="col-2 q-px-sm"
                  dense
                  clearable
                  outlined
                  filled
                  v-model="filters.dateTo"
                  label="Дата окончания"
                  type="date"
              />
            </div>
            <div class="row q-mt-sm q-ml-sm">
              <q-btn class="text-right" dense size="sm" @click="refresh" label="Применить фильтры" color="primary" />
            </div>
          </div>
          <OperationTable
              :operations="operations"
              :pagination="pagination"
              @update:pagination="(newPagination) => { pagination = newPagination; refresh(); }"
              @refresh="refresh"
          />
        </div>
      </div>
    </div>
  </q-page>
</template>

<style scoped>

</style>