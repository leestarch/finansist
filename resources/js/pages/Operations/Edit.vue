<script setup>
import {onMounted, ref} from 'vue'
import {Loading, Notify} from 'quasar'
import axios from 'axios'
import {useRoute} from "vue-router";
import {format} from "date-fns";

const route = useRoute()
const operationId = route.params.id
const pizzerias = ref([])
const isContractorLoading = ref(false)
const contractorsOptions = ref([])
const categories = ref([])
const filteredCategories = ref([])
const amountError = ref({
  message: '',
  difference: 0,
})

const form = ref({
  pizzeria: null,
  sber_amountRub: '',
  description: '',
  is_completed: false,
  date: '',
  contractor: null,
  categories: [],
  type: null
})

const processInput = (inputText) => {
  const cleanedInput = inputText.replace(/,/g, '.');
  const cleanedAndReplaced = cleanedInput.replace(/[^\d.-]/g, '');
  const isValidNumber = /^-?\d*\.?\d*$/.test(cleanedAndReplaced);

  if (isValidNumber) {
    const result = parseFloat(cleanedAndReplaced);
    form.value.amount = isNaN(result) ? '' : result;
  } else {
    form.value.amount = '';
  }
}

const handleInput = () => {
  processInput(form.value.amount);
}

const refresh = async () => {
  try {
    const response = await axios.get(`/api/operations/${operationId}`, {
      params: {
        include: 'payeeContractor,categories'
      }
    })

    form.value.sber_amountRub = response.data.data.sber_amountRub
    form.value.date = format(new Date(response.data?.data?.date_at), 'yyyy-MM-dd')
    form.value.is_completed = response.data.data.is_completed
    form.value.pizzeria = pizzerias.value.find(pizzeria => pizzeria.id === response.data.data.pizzeria_id)
    form.value.contractor = response.data.data.payee_contractor
    form.value.description = response.data.data.sber_paymentPurpose
    form.value.categories = response.data.data.categories.map(cat => {
      return {
        category: {
          id: cat.id,
          name: cat.name
        },
        amount: response.data.data.categories.length > 1 ? cat.sber_amountRub: form.value.sber_amountRub,
        percent: response.data.data.categories.length > 1 ? ((cat.sber_amountRub / form.value.sber_amountRub) * 100).toFixed(2) : 100
      }
    })

  } catch (error) {
    console.log(
        error
    )
    Notify.create({
      message: 'Ошибка получения данных операции',
      color: 'red'
    })
  }
}

const fetchPizzerias = async () => {
  try{
    const response = await axios.get('/api/pizzerias')
    pizzerias.value = response.data.data
  }catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}

const fetchCategories = async () => {
  try{
    const response = await axios.get('/api/categories', {
      params: {
        full_list: true
      }
    })
    categories.value = response.data.data
    filteredCategories.value = response.data.data
  }catch (e) {
    Notify.create({
      message:'Ошибка получения данных',
      color:'red',
      timeout: 2000
    })
  }
}
const fetchContractors = async (val, id='') => {
  try {
    const response = await axios.get('/api/contractors', {
      params: {
        q: val || '',
        id: id
      },
    });
    contractorsOptions.value = response.data.data;
  } catch (error) {
    Notify.create({
      message: 'Ошибка получения контрагентов',
      color: 'red',
      timeout: 2000
    });
  } finally {
    isContractorLoading.value = false;
  }
};

const onContractorChange  = async (val, update) => {
  if (val.length > 4) {
    isContractorLoading.value = true;
    await fetchContractors(val);
    update(() => contractorsOptions.value);

  } else {
    contractorsOptions.value = [];
    update(() => contractorsOptions.value);
  }
};


const submitForm = async () => {
  if(amountError.value.difference !== 0){
    Notify.create({
      message: 'Сумма категорий не равна сумме операции',
      color: 'red'
    })
    return
  }

  const total = form.value.categories.reduce(
      (acc, cat) => acc + (parseFloat(cat.amount) || 0),
      0
  );

  if(total !== parseFloat(form.value.sber_amountRub)) {
    Notify.create({
      message: 'Сумма категорий не равна сумме операции',
      color: 'red'
    })
    return
  }

  Loading.show()
  try {
    const response = await axios.put(`/api/operations/${operationId}`, {
      pizzeria_id: form.value.pizzeria.id,
      date_at: form.value.date,
      is_completed: form.value.is_completed,
      payee_contractor_id: form.value.contractor.id,
      sber_paymentPurpose: form.value.description,
      sber_amountRub: form.value.sber_amountRub,
      categories: form.value.categories.map(cat => {
        return {
          id: cat.category.id,
          sber_amountRub: cat?.amount
        }
      })
    })

    if (response?.data?.success) {
      Notify.create({
        message: 'Данные успешно обновлены',
        color: 'green'
      })
    }
    if (!response.data.success) {
      Notify.create({
        message: response?.data?.message,
        color: 'red'
      })
    }

  } catch (error) {
    console.log(
        error
    )
    Notify.create({
      message: 'Проверьте правильность заполнения полей',
      color: 'red'
    })
  }
  Loading.hide()
}


const onAmountInputChange = (val, item) => {
  const total = form.value.categories.reduce(
      (acc, cat) => acc + (parseFloat(cat.amount) || 0),
      0
  );

  const difference = parseFloat(form.value.sber_amountRub) - total;
  const percent = (parseFloat(item.row.amount) / parseFloat(form.value.sber_amountRub)) * 100;
  item.row.percent = percent.toFixed(2);

  let message = '';
  if (difference < total) {
    message = 'Уменьшите сумму на: ';
  } else if (difference > total) {
    message = 'Увеличьте сумму на: ';
  }

  amountError.value = {
    message: message,
    difference: Math.abs(difference),
  };
};

const categoryColumns = ref([
  {name: 'category', label: 'Категория', align: 'left'},
  {name: 'amount', label: 'Сумма', align: 'left', style: 'width: 100px'},
  {name: 'percent', label: 'Доля', align: 'left', width: '50px'},
])

const addCategory = () => {
  form.value.categories.push({
    category: '',
    amount: 0,
    percent: 0
  })
}

const filterCategories = (val, update) => {
  if (val === '') {
    update(() => {
      filteredCategories.value = [...categories.value];
    });
  } else {
    const needle = val.toLowerCase();
    update(() => {
      filteredCategories.value = categories.value.filter((cat) =>
          cat.name.toLowerCase().includes(needle)
      );
    });
  }
};

function onClear(val) {
  console.log(val.id);
  form.value.categories = form.value.categories.filter((cat) => cat?.category !== null);
}

onMounted( () => {
  Loading.show()
  fetchPizzerias()
  fetchCategories()
  refresh()
  Loading.hide()
})
</script>

<template>
  <q-page class="row shadow-3 bg-grey-2">
    <div class="row q-mx-auto bg-white col-10 col-sm-8">
      <q-card class="bg-white q-px-xl blue col-12">
        <div class="text-h4 q-mt-md">
          Редактирование операции
        </div>
        <q-form class="q-mt-xl" @submit.prevent="submitForm">
          <div class="row items-center justify-between">
            <div class="col-2 full-height">
              Сумма:
            </div>
            <div class="col-9">
              <q-input
                  dense
                  outlined
                  v-model="form.sber_amountRub"
                  label="Сумма"
                  type="number"
                  required
                  @input="handleInput"
              />
            </div>
          </div>
          <div class="row items-center justify-between q-mt-sm">
            <div class="col-3 full-height">
              Дата:
            </div>
            <div class="col-4">
              <q-input
                  dense
                  outlined
                  v-model="form.date"
                  label="Date"
                  type="date"
                  required
              />
            </div>
            <div class="col-5 text-right">
              <q-checkbox
                  dense
                  outlined
                  v-model="form.is_completed"
                  label="Подтвердить операцию"
              />
            </div>
          </div>
          <div class="row items-center justify-between q-mt-sm">
            <div class="col-3 full-height">
              Пиццерия:
            </div>
            <div class="col-9 full-height">
              <q-select
                  class="col-3"
                  dense
                  clearable
                  outlined
                  filled
                  v-model="form.pizzeria"
                  :options="pizzerias"
                  label="Пиццерия"
                  option-value="id"
                  option-label="name"
              />
            </div>
          </div>
          <div class="row items-center justify-between q-mt-sm">
            <div class="col-3 full-height">
              Контрагент:
            </div>
            <div class="col-9 full-height">
              <q-select
                  class="col-6"
                  dense
                  outlined
                  filled
                  label="Контрагент"
                  v-model="form.contractor"
                  :options="contractorsOptions"
                  use-input
                  clearable
                  option-label="name"
                  @filter="onContractorChange"
                  :loading="isContractorLoading"
              />
            </div>
          </div>
          <div class="row items-center justify-between q-mt-sm">
            <div class="col-2 full-height">
              Назначение:
            </div>
            <div class="col-9">
              <q-input
                  type="textarea"
                  dense
                  outlined
                  v-model="form.description"
                  label="Назначение"
                  required
              />
            </div>
          </div>
          <div class="row items-center justify-between q-mt-sm">
            <div class="col-3 full-height">
              Категории:
            </div>
            <div class="col-9">
              <q-table
                  :rows="form.categories"
                  :columns="categoryColumns"
                  flat
                  bordered
                  dense
                  hide-bottom
              >
                <template v-slot:body-cell="item">
                  <q-td :item="item">
                    <template v-if="item.col.name === 'category'">
                      <div>
                        <q-select
                            dense
                            flat
                            v-model="item.row.category"
                            :options="filteredCategories"
                            option-label="name"
                            clearable
                            borderless
                            use-input
                            @filter="filterCategories"
                            @clear="onClear"
                        />
                      </div>
                    </template>
                    <template v-if="item.col.name === 'amount'">
                      <div style="min-width: 65px">
                        <q-input
                            @change="(val) => onAmountInputChange(val, item)"
                            flat
                            borderless
                            v-model="item.row.amount"
                            dense
                            type="number"
                        />
                      </div>
                    </template>
                    <template v-if="item.col.name === 'percent'">
                      {{ item.row?.percent }}%
                    </template>
                  </q-td>
                </template>
              </q-table>
              <div class="row">
                <div class="row justify-between col-6">
                  <p @click="addCategory" class="cursor-pointer text-blue-9">
                    Добавить...
                  </p>
                  <p>
                    Итого:
                  </p>
                </div>
                <div class="row col-3 justify-end">
                  <p>
                      {{ form.categories.reduce((acc, cat) => acc + (parseFloat(cat.amount) || 0), 0) }}
                  </p>
                </div>
                <div class="row col-3 justify-end">
                  <p>
                    {{ ((form.categories.reduce((acc, cat) => acc + (parseFloat(cat.amount) || 0), 0)) / form.sber_amountRub * 100).toFixed(2) }}%
                  </p>
                </div>
              </div>
              <div class="row justify-between" v-if="amountError.message">
                <div class="text-red-8 row col-8 justify-center">
                    {{ amountError.message }}
                </div>
                <div class="col-4 row justify-center">
                  {{ amountError.difference }}
                </div>
              </div>
            </div>
          </div>
          <q-btn
              class="q-mt-md"
              label="Сохранить"
              type="submit"
              color="primary"
          />
        </q-form>
      </q-card>
    </div>
  </q-page>
</template>