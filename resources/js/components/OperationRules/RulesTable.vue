<script setup>
import { ref } from 'vue';
import {Notify} from "quasar";

const props = defineProps({
  rules: Array,
  pagination: Object,
});

const emit = defineEmits(['update:pagination', 'refresh']);

const columns = ref([
  { name: 'category', label: 'Категория', field: row=>row?.category?.name, align: 'left' },
  { name: 'name', label: 'Название', field: 'name', align: 'left' },
  { name: 'purpose_expression', label: 'Регулярное выражение', field: 'purpose_expression', align: 'left' },
  { name: 'actions', label: 'Действия', align: 'left' },
]);

const deleteRule = async (id) => {
  try {
    const response = await axios.delete(`/api/operations/rules/${id}`)
    if(response?.data?.success) {
      Notify.create({
        message:'Правило успешно удалено',
        color:'green',
        timeout: 2000
      })
      emit('refresh')
    }
  }catch (e) {
    Notify.create({
      message:'Ошибка удаления',
      color:'red',
      timeout: 2000
    })
  }
}

const handlePaginationUpdate = (page) => {
  emit('update:pagination', { ...props.pagination, page });
};
</script>

<template>
  <div>
    <div v-if="rules.length" class="rounded-borders shadow-3">
      <q-table
          class="q-mt-md q-px-sm"
          :rows="rules"
          :pagination="{ rowsPerPage: pagination?.rowsPerPage }"
          :columns="columns"
          row-key="id"
          hide-bottom
          flat
      >
        <template v-slot:body-cell="item">
          <q-td :item="item">
            <template v-if="item.col.name === 'name'">
              <router-link :to="`/operations/rules/edit/${item.row.id}`">
                {{ item.row?.name }}
              </router-link>
            </template>
            <template v-if="item.col.name === 'purpose_expression'">
              {{ item.row?.purpose_expression }}
            </template>
            <template v-if="item.col.name === 'category'">
              {{ item.row?.category?.name }}
            </template>
            <template v-if="item.col.name === 'actions'">
              <div class="row justify-center">
                <q-icon
                    @click="deleteRule(item.row.id)"
                    name="delete"
                    size="sm"
                    class="cursor-pointer"
                    color="negative"
                />
              </div>
            </template>
          </q-td>
        </template>
      </q-table>
      <div class="row bg-white justify-center q-pb-md ">
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
    <div v-else>
      <p class="text-h6 text-center">
        Для этого контрагента правила не установлены
      </p>
    </div>
  </div>
</template>