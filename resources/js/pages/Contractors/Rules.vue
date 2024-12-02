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
    const response = await axios.get(`/api/rules`, {
      params: {
        contractor_id: contractorId,
        load: ['category,contractor'],
      }
    })
    console.log(response.data.data)
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
        <p class="text-blue-5 row items-center">
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