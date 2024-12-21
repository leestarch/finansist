<script setup>
import {onMounted, ref} from 'vue'
import {date, Loading, Notify} from 'quasar'
import axios from 'axios'

const categories = ref([])
const filteredCategories = ref([])
const pizzerias = ref([])
const isContractorLoading = ref(false)
const contractorsOptions = ref([])

const amountError = ref({
  message: '',
  difference: ''
})

const form = ref({
  pizzeria: null,
  sber_amountRub: '',
  sber_paymentPurpose: '',
  is_completed: false,
  date_at: '',
  direction: null,
  contractorPayer: null,
  contractorPayee: null,
  categories: [],
})

const dateFormatted = ref('')
const menuDate = ref(false)

const updateDate = (val) => {
  form.value.date_at = val;
  dateFormatted.value = date.formatDate(val, 'DD.MM.YYYY');
  menuDate.value = false;
};

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
    const response = await axios.get('/api/categories')
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
  const total = form.value.categories.reduce(
      (acc, cat) => acc + (parseFloat(cat.amount) || 0),
      0
  );

  if(form.value.categories.length && total !== parseFloat(form.value.sber_amountRub)) {
    Notify.create({
      message: 'Сумма категорий не равна сумме операции',
      color: 'red'
    })
    return
  }

  try {
    form.value.pizzeria_id = form.value?.pizzeria?.id
    const  response = await axios.post('/api/operations', {
      ...form.value,
      payer_contractor_id: form.value?.contractorPayer?.id,
      payee_contractor_id: form.value?.contractorPayee?.id,
      sber_paymentPurpose: form.value.sber_paymentPurpose,
      sber_direction: form.value.direction?.value,
      categories: form.value.categories.map(cat => ({id: cat.category.id, sber_amountRub: cat.amount}))
    })
    if (response.data.success) {
      Notify.create({
        message: 'Операция успешно создана',
        color: 'positive'
      })
      form.value = {
        pizzeria: null, sber_amountRub: '', description: '',
        is_completed: false, date: '', contractor: null, categories: [],
      }
    }
    if(!response.data.success) {
      Notify.create({
        message: response.data.message,
        color: 'red'
      })
    }
  } catch (error) {
    Notify.create({
      message: 'Ошибка отправки данных',
      color: 'red'
    })
  }
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

onMounted(() => {
  Loading.show()
  fetchPizzerias()
  fetchCategories()
  Loading.hide()
})
</script>

<template>
  <q-page class="row shadow-3 bg-grey-2">
    <div class="row q-mx-auto bg-white col-10 col-sm-8">
      <q-card class="bg-white q-px-xl blue col-12">
        <div class="text-h4 q-mt-md">
          Создание операции
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
                  class="col-2 q-px-sm q-mt-sm"
                  dense
                  outlined
                  filled
                  v-model="dateFormatted"
                  label="Дата"
                  readonly
                  @click.native.stop="menuDate = true"
              >
                <template v-slot:append>
                  <q-icon name="event" @click.stop="menuDate = true"/>
                </template>
                <q-menu v-model="menuDate" anchor="bottom left" self="top left">
                  <q-date
                      v-model="form.date_at"
                      mask="YYYY-MM-DD"
                      @update:model-value="updateDate"
                  />
                </q-menu>
              </q-input>
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
                  required
              />
            </div>
          </div>
          <div class="row items-center justify-between q-mt-sm">
            <div class="col-3 full-height">
              Контрагент (payer):
            </div>
            <div class="col-9 full-height">
              <q-select
                  class="col-6"
                  dense
                  outlined
                  filled
                  label="Контрагент"
                  v-model="form.contractorPayer"
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
            <div class="col-3 full-height">
              Контрагент (payee):
            </div>
            <div class="col-9 full-height">
              <q-select
                  class="col-6"
                  dense
                  outlined
                  filled
                  label="Контрагент"
                  v-model="form.contractorPayee"
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
              Direction:
            </div>
            <div class="col-9">
              <q-select
                  dense
                  outlined
                  required
                  filled
                  label="Тип операции"
                  v-model="form.direction"
                  :options="[
                    {label: 'Поступление (debit)', value: 'DEBIT'},
                    {label: 'Выплата (credit)', value: 'CREDIT'},
                  ]"
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
                  v-model="form.sber_paymentPurpose"
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
              <div class="row justify-between" v-if="amountError?.message">
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