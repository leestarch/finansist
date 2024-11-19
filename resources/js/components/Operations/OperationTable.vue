<script setup>

import {ref} from "vue";

const props = defineProps({
  operations: Array,
  pagination: Object,
});

const columns = ref([
  { name: 'sber_amountRub', label: 'Сумма', field: 'sber_amountRub', align: 'left' },
  { name: 'categories', label: 'Категория', field: 'categories', align: 'left' },
  { name: 'date_at', label: 'Дата', field: 'date_at', align: 'left' },
  { name: 'sber_paymentPurpose', label: 'Назначение', field: 'sber_paymentPurpose', align: 'left' },
]);

const emit = defineEmits(['update:pagination', 'refresh']);
const handlePaginationUpdate = (page) => {
  emit('update:pagination', { ...props.pagination, page });
};

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
    ></q-table>
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
  <div v-else class="row q-mt-sm bg-white justify-center q-pb-md rounded-borders shadow-3">
    <p class="text-h6">
      Для этого контрагента операции не найдены
    </p>
  </div>
</template>