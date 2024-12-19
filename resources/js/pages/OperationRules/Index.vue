<script setup>
import {onMounted, ref} from "vue";
import {Notify} from "quasar";
import RulesTable from "../../components/OperationRules/RulesTable.vue";
import axios from "axios";

const operationRules = ref([])
const contractors = ref([])
const contractorIds = ref([])
const isLoading = ref(false)

const pagination = ref({
  page: 1,
  rowsPerPage: 20,
  rowsNumber: 0,
});

const filters = ref({
  purpose_expression: '',
  operation_type: null,
  include_commons: false
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
        include_commons: Boolean(filters.value.include_commons),
        contractor_ids: contractorIds.value ? contractorIds.value.map(contractor => contractor.id) : null,
      }
    })

    pagination.value.rowsNumber = response.data?.meta?.total
    pagination.value.page = response.data?.meta?.current_page
    pagination.value.totalPages = response.data?.meta?.last_page
    operationRules.value = response?.data?.data
    console.log(response.data.data)
  } catch (e) {
    console.log(e)
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const onContractorChange  = async (val, update, abort) => {
  if (val.length > 3) {
    isLoading.value = true;
    try {
      const response = await axios.get('/api/contractors', {
        params: {
          q: val,
        },
      });
      contractors.value = response.data.data;

      update(() => contractors.value);
    } catch (error) {
      console.log(error);
      abort();
    } finally {
      isLoading.value = false;
    }
  } else {
    contractors.value = [];
    update(() => contractors.value);
  }
};

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

          <div class="col-5 row bg-grey-3 rounded-borders">
            <q-select
                class="q-px-sm col-8"
                dense
                borderless
                label="Контрагенты (получатели)"
                v-model="contractorIds"
                :options="contractors"
                use-input
                multiple
                clearable
                option-label="name"
                @filter="onContractorChange"
                :loading="isLoading"
                use-chips
            />
            <q-checkbox
                class="col-4"
                v-model="filters.include_commons"
                label="включая общие"
            />
          </div>
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