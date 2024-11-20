<script setup>
import {useRoute} from "vue-router";
import {Notify} from "quasar";
import {onMounted, ref} from "vue";
import RulesTable from "../../components/OperationRules/RulesTable.vue";

const route = useRoute()
const contractorId = route.params.id
const rules = ref([])

const pagination = ref({
  page: 1,
  rowsPerPage: 20,
  rowsNumber: 0,
});

const fetchRules = async () => {
  try {
    const response = await axios.get(`/api/operations/rules`, {
      params: {
        contractor_id: contractorId,
        load: ['category'],
      }
    })
    rules.value = response.data.data
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

onMounted(() => {
  fetchRules()
})

</script>

<template>
  <q-page class="bg-grey-3">
    <div class="row justify-center">
      <div class="justify-center col-12 q-px-md q-mt-lg">
        <p class="text-h6 text-center">
          Правила
        </p>
        <RulesTable
            :rules="rules"
            :pagination="pagination"
            @update:pagination="(newPagination) => { pagination = newPagination; fetchRules(); }"
            @refresh="fetchRules"
        />
      </div>
    </div>
  </q-page>
</template>

<style scoped>

</style>