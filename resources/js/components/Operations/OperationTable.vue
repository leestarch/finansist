<script setup>

import {ref} from "vue";
import {Loading, Notify} from "quasar";

const props = defineProps({
  operations: Array,
  pagination: Object,
});

const columns = ref([
  { name: 'sber_amountRub', label: 'Сумма', field: 'sber_amountRub', align: 'left' },
  { name: 'categories', label: 'Категория', field: 'categories', align: 'left' },
  { name: 'is_manual', label: 'is manual', field: 'is_manual', align: 'left' },
  { name: 'actions', label: 'Действия', align: 'left' },
  { name: 'date_at', label: 'Дата', field: 'date_at', align: 'left' },
  { name: 'sber_paymentPurpose', label: 'Назначение', field: 'sber_paymentPurpose', align: 'left' },
]);

const emit = defineEmits(['update:pagination', 'refresh']);
const handlePaginationUpdate = (page) => {
  emit('update:pagination', { ...props.pagination, page });
};

const handleIsManualChange = async (row) => {
  Loading.show()
  try {
    const response = await axios.put(`/api/operations/${row.id}`, {is_manual: row.is_manual})
    if (response?.data?.success) {
      Notify.create({
        message: 'Обновлено',
        color: 'green',
        timeout: 2000
      })
    }
  }catch (e) {
    Notify.create({
      message: 'Ошибка обновления',
      color: 'red',
      timeout: 2000
    })
  }
  Loading.hide()
}

</script>
<template>
  <div v-if="operations.length" class="shadow-3">
    <q-table
        flat
        class="q-mt-md q-px-sm"
        :rows="operations"
        :pagination="{ rowsPerPage: pagination.rowsPerPage }"
        :columns="columns"
        row-key="id"
        hide-bottom
    >

      <template v-slot:body-cell="item">
        <q-td :item="item">
          <template v-if="item.col.name === 'sber_amountRub'">
            {{ item.row?.sber_amountRub }}
          </template>
          <template v-if="item.col.name === 'categories'">
            {{ item.row?.categories.map(category => category.name).join(', ') }}
          </template>
          <template v-if="item.col.name === 'is_manual'">
            <q-checkbox
                @update:model-value="(val) => item.row.is_manual = val"
                @update:modelValue="handleIsManualChange(item.row)"
                :model-value="item.row.is_manual"
            />
          </template>
          <template v-if="item.col.name === 'actions'">
            <div>
              <router-link
                  :to="{name: 'OperationEdit', params: {id: item.row.id}}"
                  class="cursor-pointer"
              >
                <q-icon
                    color="primary"
                    size="xs"
                    name="edit"
                    class="cursor-pointer"
                    @click="editOperation(item.row.id)"
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