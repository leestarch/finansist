<script setup>
import { defineProps } from 'vue';

defineProps({
  pagination: Object,
});

const handlePageChange = (page) => {
  console.log(123)
};
</script>

<template>
  <q-pagination
      v-model="pagination.page"
      :max="Math.ceil(pagination.rowsNumber / pagination.rowsPerPage)"
      boundary-numbers
      boundary-links
      @update:model-value="handlePageChange"
      class="text-center q-mt-md"
  />
</template>