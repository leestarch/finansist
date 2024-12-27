<script setup>
import OperationTable from "../../components/Operations/OperationTable.vue";
import {onMounted, ref} from "vue";
import {Loading, Notify} from "quasar";
import axios from "axios";
import {useRoute} from "vue-router";

const route = useRoute()

const category = ref({})
const operations = ref([])
const pagination = ref({
  page: 1,
  rowsPerPage: 30,
  rowsNumber: 0
});

const totalAmount = ref(0)
const totalItems = ref(0)

const id = route.params.id

const refresh = async () => {
  Loading.show();
  try {
    const response = await axios.get('/api/operations/', {
      params: {
        page: pagination.value.page,
        categoryIds: [id],
      }
    })

    operations.value = response?.data?.data
    totalAmount.value = operations.value.reduce((acc, operation) => acc + operation?.sber_amountRub, 0)
    totalItems.value = response.data.meta.total

    pagination.value.rowsNumber = response.data?.meta?.total
    pagination.value.page = response.data?.meta?.current_page
    pagination.value.totalPages = response.data?.meta?.last_page
  } catch (e) {
    console.log(e)
    Notify.create({
      message: 'Ошибка получения данных',
      color: 'red',
      timeout: 2000
    })
  }
  Loading.hide()
}

onMounted(async () => {
  await refresh()
})

</script>

<template>
  <q-page>
    <q-card>
      <q-card-section class="text-h5 text-center">
        Операции категории
      </q-card-section>
    </q-card>
    <div class="q-pt-md q-pl-md info-text">
      Всего: {{totalItems}}
      <br>
      Сумма: {{totalAmount}}
    </div>
    <q-card>
      <operation-table :operations="operations"
                       :pagination="pagination"
                       @update:pagination="(newPagination) => { pagination = newPagination; refresh(); }"
                       @refresh="refresh"/>
    </q-card>
  </q-page>
</template>

<style scoped>
.info-text {
  font-size: 18px;
  font-weight: bold;
}
</style>