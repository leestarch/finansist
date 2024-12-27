<script setup>

import {ref} from "vue";
import {Loading, Notify} from "quasar";

const props = defineProps({
  operations: Array,
  pagination: Object,
});

const columns = ref([
  {
    name: 'sber_amountRub',
    label: 'Сумма',
    field: 'sber_amountRub',
    align: 'left',
    sortable: true,
    sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
  },
  {
    name: 'categories',
    label: 'Категория',
    field: 'categories',
    align: 'left',
    sortable: true,
    sort: (a, b, rowA, rowB) => {
      const nameA = rowA.categories?.[0]?.name || '';
      const nameB = rowB.categories?.[0]?.name || '';
      return nameA.localeCompare(nameB);
    },
  },
  {
    name: 'contractor',
    label: 'Контрагент',
    field: 'payee_contractor',
    align: 'left',
    sortable: true,
    sort: (a, b, rowA, rowB) => {
      const nameA = rowA.payee_contractor?.full_name || '';
      const nameB = rowB.payee_contractor?.full_name || '';
      return nameA.localeCompare(nameB);
    },
  },
  {
    name: 'is_manual',
    label: 'is manual',
    field: 'is_manual',
    align: 'left',
    sortable: true,
    sort: (a, b) => Number(a) - Number(b),
  },
  {
    name: 'direction',
    label: 'Направление',
    field: 'direction',
    sortable: true
  },
  {
    name: 'actions',
    label: 'Действия',
    align: 'left',
  },
  {
    name: 'date_at',
    label: 'Дата',
    field: 'date_at',
    align: 'left',
    sortable: true,
    sort: (a, b) => new Date(a) - new Date(b),
  },
  {
    name: 'sber_paymentPurpose',
    label: 'Назначение',
    field: 'sber_paymentPurpose',
    align: 'left',
  },
]);

const emit = defineEmits(['update:pagination', 'refresh']);
const handlePaginationUpdate = (page) => {
  emit('update:pagination', {...props.pagination, page});
};

const handleIsManualChange = async (row) => {
  Loading.show()
  try {
    const response = await axios.put(`/api/operations/${row?.id}`, {is_manual: row.is_manual})
    if (response?.data?.success) {
      Notify.create({
        message: 'Обновлено',
        color: 'green',
        timeout: 2000
      })
    }
  } catch (e) {
    Notify.create({
      message: 'Ошибка обновления',
      color: 'red',
      timeout: 2000
    })
  }
  Loading.hide()
}

const formatSum = (val) => {
  return new Intl.NumberFormat('ru-RU', {style: 'currency', currency: 'RUB'}).format(val)
}

</script>

<template>
  <div v-if="operations.length" class="shadow-3">
    <q-table
        flat
        class="q-mt-md q-px-sm fixed-table"
        :rows="operations"
        :pagination="{ rowsPerPage: pagination.rowsPerPage }"
        :columns="columns"
        row-key="id"
        hide-bottom
    >
      <template v-slot:body-cell="item">
        <q-td :item="item" class="fixed-cell">
          <template v-if="item.col.name === 'sber_amountRub'">
            {{ (formatSum(item.row?.sber_amountRub)) }}
          </template>
          <template v-if="item.col.name === 'categories'">
            <template v-if="item.row?.categories.length > 1">
              <span>
                <template v-for="(category, index) in item.row.categories">
                  <span>
                    {{ category.name }}
                    <span class="text-primary">
                      ({{ formatSum(category.sber_amountRub)}})
                    </span>
                    <span v-if="index < item.row.categories.length - 1">, </span>
                  </span>
                </template>
              </span>
            </template>
            <template v-else>
              <router-link v-if="item.row?.categories?.[0]?.id"
                           :to="{name: 'CategoriesEdit', params: {id: item.row?.categories?.[0]?.id}}" target="_blank">
                {{ item.row?.categories?.map(category => category?.name).join(', ') }}
              </router-link>
            </template>
          </template>
          <template v-if="item.col.name === 'contractor'">
            <router-link v-if="item.row?.payee_contractor?.id"
                         :to="{name: 'ContractorShow', params: {id: item.row?.payee_contractor?.id}}" target="_blank">
              <div>{{ item.row?.payee_contractor?.full_name }}</div>
            </router-link>
          </template>
          <template v-if="item.col.name === 'is_manual'">
            <q-checkbox
                @update:model-value="(val) => item.row.is_manual = val"
                @update:modelValue="handleIsManualChange(item.row)"
                :model-value="item.row.is_manual"
            />
          </template>
          <template v-if="item.col.name === 'direction'">
            {{ item.row?.direction }}
          </template>
          <template v-if="item.col.name === 'actions'">
            <div>
              <router-link
                  :to="{name: 'OperationEdit', params: {id: item?.row?.id}}"
                  class="cursor-pointer"
              >
                <q-icon
                    color="primary"
                    size="xs"
                    name="edit"
                    class="cursor-pointer"
                />
              </router-link>
            </div>
          </template>
          <template v-if="item.col.name === 'date_at'">
            {{ item.row?.date_at }}
          </template>
          <template v-if="item.col.name === 'sber_paymentPurpose'">
            {{ item.row?.sber_paymentPurpose }}
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
      >
      </q-pagination>
    </div>
  </div>
  <div v-else class="row q-mt-sm bg-white justify-center q-pb-md rounded-borders shadow-3">
    <p class="text-h6">
      Для этого контрагента операции не найдены
    </p>
  </div>
</template>

<style scoped>
.fixed-table {
  width: 100%;
  table-layout: fixed; /* Фиксированная ширина столбцов */
}

.fixed-cell {
  word-break: break-word; /* Перенос текста внутри ячейки */
  white-space: normal;    /* Разрешение переноса */
  overflow: hidden;       /* Обрезка избыточного текста */
}
</style>