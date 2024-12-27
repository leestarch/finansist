<script setup>
import { ref } from 'vue';
import {Notify} from "quasar";
import {createRouter as $router, useRoute} from "vue-router";

const route = useRoute()
const props = defineProps({
  rules: Array,
  pagination: Object,
});

const emit = defineEmits(['update:pagination', 'refresh']);

const columns = ref([
  { name: 'category', label: 'Категория', field: row=>row?.category?.name, align: 'left', sortable: true, sort: (a,b, rowA, rowB) =>  {
      const nameA = rowA.category?.name || '';
      const nameB = rowB.category?.name || '';
      return nameA.localeCompare(nameB);
    }},
  { name: 'contractor', label: 'Контрагент', field: row=>row?.contractor?.full_name, align: 'left',  sortable: true, sort: (a,b, rowA, rowB) =>  {
      const nameA = rowA.contractor?.full_name || '';
      const nameB = rowB.contractor?.full_name || '';
      return nameA.localeCompare(nameB);
    } },
  { name: 'purpose_expression', label: 'Регулярное выражение', field: 'purpose_expression', align: 'left' },
  { name: 'actions', label: 'Действия', align: 'left' },
]);

const deleteRule = async (id) => {
  try {
    const response = await axios.delete(`/api/rules/${id}`)
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
            <template v-if="item.col.name === 'purpose_expression'">
              {{ item.row?.purpose_expression }}
            </template>
            <template v-if="item.col.name === 'category'">
              <router-link :to="{name: 'CategoriesEdit', params: {id: item.row?.category?.id}}" target="_blank">{{ item.row?.category?.name }}</router-link>
            </template>
            <template v-if="item.col.name === 'contractor'">
              <template v-if="!item.row?.contractor?.name">
                Для всех
              </template>
              <template v-if="item.row?.contractor?.full_name">
                <router-link :to="{name: 'ContractorShow', params: {id: item.row?.contractor?.id}}" target="_blank">
                  <div>{{ item.row?.contractor?.full_name }}</div>
                </router-link>
              </template>
            </template>
            <template v-if="item.col.name === 'actions'">
              <div class="row justify-between">
                <router-link class="q-mr-sm" :to="{name: 'EditRule', params: {id: item?.row?.id}}">
                  <q-icon
                      name="edit"
                      size="xs"
                      class="cursor-pointer"
                      color="primary"
                  />
                </router-link>
                <q-icon
                    @click="deleteRule(item.row.id)"
                    name="delete"
                    size="xs"
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