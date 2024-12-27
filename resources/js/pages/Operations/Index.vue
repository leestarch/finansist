<script setup>
import {onMounted, ref, watch} from "vue";
import {useRoute, useRouter} from "vue-router";
import {Loading, Notify, date} from "quasar";
import OperationTable from "../../components/Operations/OperationTable.vue";
import {
  startOfMonth,
  endOfMonth,
  startOfWeek,
  endOfWeek,
  startOfQuarter,
  addWeeks,
  endOfQuarter,
  parse,
  format
} from 'date-fns';
import axios from "axios";

const route = useRoute();
const router = useRouter();

const pizzerias = ref([])
const pizzeriaIds = ref(null)

const filterParams = route.query
const operations = ref([])

const contractorsOptions = ref([])
const contractors = ref([])
const contractorIds = ref([])
const isLoading = ref(false)

const operationSyncDialog = ref(false)
const syncStart_at = ref(null)
const syncEnd_at = ref(null)
const start_at_menu = ref(false)
const end_at_menu = ref(false)
const start_at_formatted = ref('')
const end_at_formatted = ref('')


const categories = ref([])
const categoryIds = ref([])
const categoriesOptions = ref([])

const totalAmount = ref(0)


const pagination = ref({
  page: 1,
  rowsPerPage: 50,
  rowsNumber: 0
});

const filters = ref({
  dateFrom: '',
  dateTo: '',
  splitFilter: null,
  categoryQuery: '',
  parentCategoryId: null,
  categories: [],
  pizzerias: [],
  purpose_expression: '',
  sberDirection: null,
});

const dateFromFormatted = ref('')
const dateToFormatted = ref('')
const menuDateFrom = ref(false)
const menuDateTo = ref(false)

const updateDateFrom = (val) => {
  filters.value.dateFrom = val;
  dateFromFormatted.value = date.formatDate(val, 'DD.MM.YYYY');
  menuDateFrom.value = false;
};
const updateDateTo = (val) => {
  filters.value.dateTo = val;
  dateToFormatted.value = date.formatDate(val, 'DD.MM.YYYY');
  menuDateTo.value = false;
};

const updateStart_at = (val) => {
  syncStart_at.value = val;
  start_at_formatted.value = date.formatDate(val, 'DD.MM.YYYY');
  start_at_menu.value = false;
};
const updateEnd_at = (val) => {
  syncEnd_at.value = val;
  end_at_formatted.value = date.formatDate(val, 'DD.MM.YYYY');
  end_at_menu.value = false;
};

watch(filters, () => {
  pagination.value.page = 1
}, {deep: true})

const refresh = async (p) => {
  Loading.show();
  try {
    const response = await axios.get('/api/operations/', {
      params: {
        ...filters.value,
        isSplit: filters.value.splitFilter?.value,
        page: pagination.value.page,
        contractorIds: contractorIds.value,
        categoryIds: filters.value.categories?.map(category => category.id) ?? null,
        pizzeriaIds: filters.value?.pizzerias?.map(pizzeria => pizzeria.id) || [pizzeriaIds.value],
      }
    })

    operations.value = response?.data?.data
    totalAmount.value = operations.value.reduce((acc, operation) => acc + operation?.sber_amountRub, 0)

    pagination.value.rowsNumber = response.data?.meta?.total
    pagination.value.page = response.data?.meta?.current_page
    pagination.value.totalPages = response.data?.meta?.last_page
  } catch (e) {
    console.log(e)
    Notify.create({
      message: 'Ошибка получения данных',
      color: 'red',
      timeout: 2000
    })
  }
  Loading.hide()
}

const loadOperations = async () => {
  try {
    Notify.create({message: 'Синхронизация запущена!'})
    operationSyncDialog.value = false
    const response = await axios.get('/api/get-operations', {
      params: {
        start_at: syncStart_at.value,
        end_at: syncEnd_at.value
      }
    })
    Notify.create({message: 'Синхронизация закончена!'})
  } catch (e) {
    Notify.create({message: 'Ошибка! Синхронизация транзакций не удалась!', color: 'error'})
  }

}

const onCategoriesChange = async (val, update, abort) => {

  if (val.length > 3) {
    isLoading.value = true;
    await fetchCategories(val);
    update(() => categoriesOptions.value);
    isLoading.value = false;
  } else {
    categoriesOptions.value = [];
    update(() => categoriesOptions.value);
  }
};

const fetchCategories = async (val = '') => {
  try {
    const response = await axios.get('/api/categories', {
      params: {
        q: val || '',
      },
    });

    categoriesOptions.value = response.data.data;
  } catch (e) {
    Notify.create({
      message: 'Ошибка получения данных',
      color: 'red',
      timeout: 2000
    })
  }
}
const onContractorChange = async (val, update, abort) => {
  if (val.length > 3) {
    isLoading.value = true;
    await fetchContractors(val);
    update(() => contractorsOptions.value);

  } else {
    contractorsOptions.value = [];
    update(() => contractorsOptions.value);
  }
};

const fetchContractors = async (val) => {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/contractors', {
      params: {
        q: val || '',
        ids: contractorIds.value,
      },
    });
    contractorsOptions.value = response.data.data;
    contractors.value = contractorsOptions.value.map((contractor) => ({
      ...contractor,
      value: contractor.id,
      label: contractor.name,
    }));
    contractorIds.value = contractorsOptions.value.map((contractor) => contractor.id);
  } catch (error) {
  } finally {
    isLoading.value = false;
  }
};

const fetchPizzerias = async () => {
  try {
    const response = await axios.get('/api/pizzerias');
    pizzerias.value = response.data.data;
  } catch (error) {
  }
};

const applyFilters = () => {
  const sanitizedFilters = JSON.parse(JSON.stringify(filters.value));
  refresh(sanitizedFilters)
};


onMounted(async () => {
  await fetchPizzerias()

  await parseParams(filterParams)

  if (contractorIds.value.length)
    await fetchContractors()

  if (pizzeriaIds.value.length) {
    filters.value.pizzerias = pizzerias.value.filter(pizzeria =>
        pizzeriaIds.value.includes(String(pizzeria.id)))
  }

  await refresh()
})

const parseParams = async (filterParams) => {
  contractorIds.value = filterParams.contractorIds || [];
  filters.value.parentCategoryId = filterParams.parentCategoryId || null;

  pizzeriaIds.value = filterParams.pizzeriaIds || [];

  if (filterParams?.groupBy && filterParams?.date) {
    const {groupBy, date} = filterParams;

    switch (groupBy) {
      case 'monthly': {
        const parsedDate = parse(date, 'MM-yyyy', new Date());
        updateDateFrom(format(startOfMonth(parsedDate), 'yyyy-MM-dd'));
        updateDateTo(format(endOfMonth(parsedDate), 'yyyy-MM-dd'));
        break;
      }
      case 'weekly': {
        const [week, year] = date.split('-').map(Number);
        const firstDayOfYear = new Date(year, 0, 1);
        const weekStartDate = addWeeks(firstDayOfYear, week - 1);

        updateDateFrom(format(startOfWeek(weekStartDate, {weekStartsOn: 1}), 'yyyy-MM-dd')); // Monday
        updateDateTo(format(endOfWeek(weekStartDate, {weekStartsOn: 1}), 'yyyy-MM-dd')); // Sunday
        break;
      }
      case 'quarterly': {
        const parsedDate = parse(date, 'Q-yyyy', new Date());
       updateDateFrom(format(startOfQuarter(parsedDate), 'yyyy-MM-dd'));
       updateDateTo(format(endOfQuarter(parsedDate), 'yyyy-MM-dd'));
        break;
      }
      default:
        const parsedDate = parse(date, 'd-MM-yyyy', new Date());
       updateDateFrom(format(parsedDate, 'yyyy-MM-dd'));
       updateDateTo(format(parsedDate, 'yyyy-MM-dd'));
    }
  }
}

const clearFilters = async () => {
  await router.push({path: route.path, query: {}});
}

</script>
<template>
  <q-page v-if="operations">
    <q-dialog v-model="operationSyncDialog">
      <q-card style="min-width: 300px;">
        <q-input
            class="col-2 q-px-sm q-mt-sm"
            dense
            outlined
            filled
            v-model="start_at_formatted"
            label="Дата начала"
            readonly
            @click.native.stop="start_at_menu = true"
        >
          <template v-slot:append>
            <q-icon name="event" @click.stop="start_at_menu = true"/>
          </template>
          <q-menu v-model="start_at_menu" anchor="bottom left" self="top left">
            <q-date
                v-model="syncStart_at"
                mask="YYYY-MM-DD"
                @update:model-value="updateStart_at"
            />
          </q-menu>
        </q-input>

        <q-input
            class="col-2 q-px-sm q-mt-sm"
            dense
            outlined
            filled
            v-model="end_at_formatted"
            label="Дата конца"
            readonly
            @click.native.stop="end_at_menu = true"
        >
          <template v-slot:append>
            <q-icon name="event" @click.stop="end_at_menu = true"/>
          </template>
          <q-menu v-model="end_at_menu" anchor="bottom left" self="top left">
            <q-date
                v-model="syncEnd_at"
                mask="YYYY-MM-DD"
                @update:model-value="updateEnd_at"
            />
          </q-menu>
        </q-input>

        <q-btn
            @click="loadOperations"
            label="Сохранить"
            color="primary"
            class="q-mt-md full-width"
        />
      </q-card>
    </q-dialog>
    <q-form @submit.prevent="applyFilters" class="items-center q-pa-md bg-grey-4">
        <div class="row">

          <q-input
              class="col-3 q-px-sm q-mt-sm"
              clearable
              dense
              outlined
              filled
              type="number"
              v-model="filters.sum"
              label="Сумма"
          />

          <q-input
              class="col-2 q-px-sm q-mt-sm"
              dense
              outlined
              filled
              v-model="dateFromFormatted"
              label="Дата начала"
              readonly
              @click.native.stop="menuDateFrom = true"
          >
            <template v-slot:append>
              <q-icon name="event" @click.stop="menuDateFrom = true"/>
            </template>
            <q-menu v-model="menuDateFrom" anchor="bottom left" self="top left">
              <q-date
                  v-model="filters.dateFrom"
                  mask="YYYY-MM-DD"
                  @update:model-value="updateDateFrom"
              />
            </q-menu>
          </q-input>
          <q-input
              class="col-2 q-px-sm q-mt-sm"
              dense
              outlined
              filled
              v-model="dateToFormatted"
              label="Дата окончания"
              readonly
              @click.native.stop="menuDateTo = true"
          >
            <template v-slot:append>
              <q-icon name="event" @click.stop="menuDateTo = true"/>
            </template>
            <q-menu v-model="menuDateTo" anchor="bottom left" self="top left">
              <q-date
                  v-model="filters.dateFrom"
                  mask="YYYY-MM-DD"
                  @update:model-value="updateDateTo"
              />
            </q-menu>
          </q-input>

        <q-select
            class="col-3 q-px-sm q-mt-sm"
            dense
            outlined
            filled
            flat
            label="Категория"
            v-model="filters.categories"
            :options="categoriesOptions"
            option-label="name"
            clearable
            multiple
            use-input
            borderless
            use-chips
            @filter="onCategoriesChange"
            :loading="isLoading"
        />
        <q-select
            class="col-3 q-px-sm q-mt-sm"
            dense
            outlined
            filled
            label="Пиццерия"
            v-model="filters.pizzerias"
            :options="pizzerias"
            clearable
            option-label="name"
            option-value="id"
            multiple
        >
        </q-select>

        <q-input
            class="col-3 q-px-sm q-mt-sm"
            clearable
            dense
            outlined
            filled
            v-model="filters.purpose_expression"
            label="Регулярное выражение"
        />

        <q-select
            class="col-3 q-px-sm q-mt-sm"
            clearable
            dense
            outlined
            filled
            v-model="filters.sberDirection"
            label="Тип операции"
            :options="[
                {label: 'DEBIT', value: 'DEBIT'},
                {label: 'CREDIT', value: 'CREDIT'},
              ]"
        />

        <q-select
            class="col-6 q-mt-sm"
            dense
            outlined
            filled
            label="Контрагенты (получатели)"
            v-model="contractors"
            :options="contractorsOptions"
            use-input
            multiple
            clearable
            option-label="name"
            @filter="onContractorChange"
            :loading="isLoading"
        />
        <q-select
            class="col-3 q-px-sm q-mt-sm"
            clearable
            dense
            outlined
            filled
            v-model="filters.splitFilter"
            label="Разделенные операции"
            :options="[
                {label: 'Все', value: null},
                {label: 'Разделенные', value: true},
                {label: 'Неразделенные', value: false},
              ]"
        />
      </div>

      <div class="row justify-between items-center q-mt-md">
        <div class="row justify-between full-width">
          <div>
            <q-btn
                @click="operationSyncDialog = true"
                label="Синхронизировать транзакции"
                dense
                size="sm"
                color="primary"
                class="q-mr-sm q-pa-xs"
            />
            <q-btn
                to="/operations/create"
                class="text-right q-pa-xs"
                dense
                size="sm"
                label="Создать операцию"
                color="primary"
            />
          </div>
          <div>
            <p class="text-body2 q-px-sm q-py-xs text-primary rounded-borders primary-borders">Итого: {{ totalAmount.toFixed(2) }}</p>
          </div>
        </div>
      </div>
      <div class="row justify-end q-mt-md">
        <q-btn class="text-right q-pa-xs" dense size="sm" type="submit" label="Применить фильтры" color="green"/>
        <q-btn class="text-right q-ml-sm q-pa-xs" dense size="sm" @click="clearFilters" label="Очистить фильтры"
               color="red"/>
      </div>
    </q-form>
    <OperationTable
        :operations="operations"
        :pagination="pagination"
        @update:pagination="(newPagination) => { pagination = newPagination; refresh(); }"
        @refresh="refresh"
    />
  </q-page>
</template>

<style scoped>
</style>
