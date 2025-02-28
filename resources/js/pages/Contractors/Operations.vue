<script setup>
import {useRoute} from "vue-router";
import {Loading, Notify} from "quasar";
import {computed, onMounted, ref, watch} from "vue";
import OperationTable from "../../components/Operations/OperationTable.vue";

const route = useRoute()
const contractorId = route.params.id
const operations = ref([])
const isChecking = ref(false)
const contractor = ref({})

const pagination = ref({
  page: 1,
  rowsPerPage: 20,
  rowsNumber: 0,
});

const dateAtMenu = ref(false)
const dateFromMenu = ref(false)
const dateToMenu = ref(false)

const filters = ref({
  purposeQuery: '',
  categoryQuery: '',
  dateAt: null,
  dateFrom: null,
  dateTo: null,
  payeeContractorIds: [],
})

watch(filters, () => {
  pagination.value.page = 1
}, {deep: true})

const refresh = async () => {
  try {
    const response = await axios.get(`/api/operations`, {
      params: {
        payeeContractorId: contractorId,
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

const fetchContractors = async () => {
  try {
    const response = await axios.get('/api/contractors/' + contractorId)
    contractor.value = response.data.data
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const checkContractorsOperation = async () => {
  Loading.show()
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
  Loading.hide()
}

const dateAtFormatted = computed(() => {
  if(filters?.value?.dateAt) {
    let [year, month, day] = filters.value.dateAt?.split('-')
    return `${day}.${month}.${year}`
  }
  return '';
})

const dateFromFormatted = computed(() => {
  if(filters?.value?.dateFrom) {
    let [year, month, day] = filters.value.dateFrom?.split('-')
    return `${day}.${month}.${year}`
  }
  return '';
})

const dateToFormatted = computed(() => {
  if(filters?.value?.dateTo) {
    let [year, month, day] = filters.value.dateTo?.split('-')
    return `${day}.${month}.${year}`
  }
  return '';
})

onMounted(() => {
  fetchContractors()
  refresh()
})

</script>

<template>
  <q-page class="bg-grey-3">
    <div class="row justify-center">
      <div class="row justify-center col-12 q-px-md q-pt-lg q-mb-sm">
        <p class="text-blue-5 row full-width">
          <router-link
              :to="{ name: 'ContractorShow', params: { id: contractorId }}"
              custom
              v-slot="{ navigate }"
          >
            <div @click="navigate" class="cursor-pointer">
              <q-icon size="sm" name="keyboard_arrow_left" />
              Назад
            </div>
          </router-link>
        </p>
        <div class="col-12 bg-white shadow-24 q-pa-md rounded-borders q-mb-md">
          <div class="row justify-center">
            <span class="text-h6">
              Информация о контрагенте
            </span>
          </div>
          <div class="row">
            <div class="col-12 text-h6">
              Имя контрагента: {{contractor.full_name}}
            </div>
            <div class="col-12 text-h6">
              ИНН контрагента: {{contractor.inn_kpp}}
            </div>
            <div>
              <q-btn
                  class="q-my-sm q-mr-sm q-px-sm"
                  dense
                  no-caps
                  size="md"
                  label="Проверить"
                  icon="assignment_turned_in"
                  @click="checkContractorsOperation"
                  color="primary"
              >
                <q-tooltip>
                  Проверить операции контрагента
                </q-tooltip>
              </q-btn>
              <q-btn
                  class="q-my-sm q-px-md"
                  dense
                  no-caps
                  size="md"
                  label="Создать"
                  icon="add_circle_outline"
                  @click="checkContractorsOperation"
                  color="primary"
              >
                <q-tooltip>
                  Создать новую операцию
                </q-tooltip>
              </q-btn>
            </div>
          </div>
        </div>

        <div class="col-12 bg-grey-3 q-px-sm ">
          <div class="bg-white shadow-3 q-px-sm rounded-borders q-pb-md">
            <p class="text-h6 text-center">
              Операции контрагента
            </p>
            <div class="row">
              <q-input
                  class="col-2 q-px-sm"
                  dense
                  outlined
                  filled
                  v-model="dateAtFormatted"
                  label="Дата"
                  readonly
                  @click.native.stop="dateAtMenu = true"
              >
                <template v-slot:append>
                  <q-icon name="event" @click.stop="dateAtMenu = true"/>
                </template>
                <q-menu v-model="dateAtMenu" anchor="bottom left" self="top left">
                  <q-date
                      v-model="filters.dateAt"
                      mask="YYYY-MM-DD"
                      @update:model-value="dateAtMenu = false"
                  />
                </q-menu>
              </q-input>

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
                  dense
                  outlined
                  filled
                  v-model="dateFromFormatted"
                  label="Дата начала"
                  readonly
                  @click.native.stop="dateFromMenu = true"
              >
                <template v-slot:append>
                  <q-icon name="event" @click.stop="dateFromMenu = true"/>
                </template>
                <q-menu v-model="dateFromMenu" anchor="bottom left" self="top left">
                  <q-date
                      v-model="filters.dateFrom"
                      mask="YYYY-MM-DD"
                      @update:model-value="dateFromMenu = false"
                  />
                </q-menu>
              </q-input>

              <q-input
                  class="col-2 q-px-sm"
                  dense
                  outlined
                  filled
                  v-model="dateToFormatted"
                  label="Дата конца"
                  readonly
                  @click.native.stop="dateToMenu = true"
              >
                <template v-slot:append>
                  <q-icon name="event" @click.stop="dateToMenu = true"/>
                </template>
                <q-menu v-model="dateToMenu" anchor="bottom left" self="top left">
                  <q-date
                      v-model="filters.dateTo"
                      mask="YYYY-MM-DD"
                      @update:model-value="dateToMenu = false"
                  />
                </q-menu>
              </q-input>
            </div>
            <div class="row justify-end q-mt-sm q-ml-sm">
              <q-btn class="text-right" dense size="sm" @click="refresh" label="Применить фильтры" color="green" />
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