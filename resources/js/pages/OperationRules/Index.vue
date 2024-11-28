<script setup>
import {onMounted, ref} from "vue";
import {Notify} from "quasar";
import RulesTable from "../../components/OperationRules/RulesTable.vue";

const operationRules = ref([])
const pagination = ref({
  page: 1,
  rowsPerPage: 20,
  rowsNumber: 0,
});

const filters = ref({
  purpose_expression: '',
  operation_type: null,
})

const refresh = async () => {
  try {
    const response = await axios.get(`/api/rules`, {
      params: {
        load: ['category,contractor'],
        paginate: pagination.value.rowsPerPage,
        page: pagination.value.page,
        purpose_expression: filters.value.purpose_expression,
        operation_type: filters.value.operation_type?.value,
      }
    })

    pagination.value.rowsNumber = response.data?.meta?.total
    pagination.value.page = response.data?.meta?.current_page
    pagination.value.totalPages = response.data?.meta?.last_page
    operationRules.value = response?.data?.data
    console.log(response.data.data)
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

onMounted(() => {
  refresh()
})

</script>

<template>
  <q-page  class="bg-grey-2">
    <div class="bg-white q-ma-sm q-pa-sm">
      <div class="bg-white shadow-3 q-px-sm rounded-borders q-pb-md">
        <p class="text-h6 text-center">
          Правила операций
        </p>
        <div class="row">
          <q-input
              class="col-3 q-px-sm"
              clearable
              dense
              outlined
              filled
              v-model="filters.purpose_expression"
              label="Регулярное выражение"
          />

          <q-select
              class="col-3 q-px-sm"
              clearable
              dense
              outlined
              filled
              v-model="filters.operation_type"
              label="Тип операции"
              :options="[
                {label: 'DEBIT', value: 'DEBIT'},
                {label: 'CREDIT', value: 'CREDIT'},
              ]"
          />
        </div>
        <div class="row q-mt-sm q-ml-sm">
          <q-btn class="text-right" dense size="sm" @click="refresh" label="Применить фильтры" color="primary" />
          <q-btn class="text-right q-ml-sm" dense size="sm" :to="{name: 'CreateRule'}"
                 label="Создать правило" color="primary" />
        </div>
      </div>
      <RulesTable
          :rules="operationRules"
          :pagination="pagination"
          @update:pagination="(newPagination) => { pagination = newPagination; refresh(); }"
          @refresh="refresh"
      />
    </div>
  </q-page>
</template>

<style scoped>

</style>