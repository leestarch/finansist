<script setup>
import {onMounted, ref, watch} from "vue";
import {useRoute} from "vue-router";
import {Notify} from "quasar";
import OperationTable from "../../components/Operations/OperationTable.vue";
import RulesTable from "../../components/OperationRules/RulesTable.vue";

const route = useRoute()
const contractorId = route.params.id
const contractor = ref({})

const refresh = async () => {
  await fetchContractor()
}

const goBack = () => {
  window.history.back();
}

const fetchContractor = async () => {
  try {
    const response = await axios.get(`/api/contractors/${contractorId}`)
    contractor.value = response.data.data
    console.log(contractor.value)
  } catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

onMounted(() => {
    refresh()
})
</script>
<template>
  <q-page class="bg-grey-2">
    <q-btn
        icon="arrow_back"
        label="Назад"
        @click="goBack"
        color="primary"
        flat
    />
    <div class="row justify-center">
      <div class="col-10 text-center bg-white rounded-borders q-pa-sm justify-center q-mt-lg">
        <div class="row text-h6">
            <p>Контрагент: {{contractor.full_name}}</p>
        </div>
        <div class="row text-h6">
          <p v-if="contractor.inn_kpp">ИНН: {{ contractor.inn_kpp }}</p>
          <p v-else>ИНН отсутствует</p>
        </div>
        <div class="row text-h6">
          <p><router-link :to="{name: 'ContractorOperations', params: {id: contractor.id}}">Опеерации</router-link></p>
        </div>
        <div class="row text-h6">
          <p><router-link :to="{name: 'ContractorRules', params: {id: contractor.id}}">Правила</router-link></p>
        </div>
      </div>
    </div>
<!--    </div>-->

  </q-page>
</template>

<style scoped>
</style>
