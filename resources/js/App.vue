<script setup>
import {onMounted, ref, provide, computed, inject} from "vue";
import {useQuasar} from "quasar";
import {useUserStore} from "./plugins/store/users.js";
import router from "./plugins/router.js";

const props = defineProps(['user_id', 'is_worker'])
const user = inject('user')
const isAuth = ref(user?.id || null);
console.log(user)

const $q = useQuasar()
const drawer = ref(false)
onMounted(async () => {
    provide('user', user)
})

const logout = () => {
    // localStorage.removeItem('token');
    // localStorage.removeItem('user');
    // $q.notify({
    //     color: 'positive',
    //     position: 'top',
    //     message: 'Вы вышли из системы',
    //     icon: 'check'
    // });
    // isAuth.value = false;
    // router.push('/login');
}
</script>

<template>
  <q-layout view="hHh lpR fFf">
      <q-header elevated class="bg-primary text-white">
          <q-toolbar>

              <q-toolbar-title>
                <q-btn flat color="" :to="{name: 'OperationIndex'}">Операции</q-btn>
                <q-btn flat color="" :to="{name: 'CategoriesTree'}">Дерево категорий</q-btn>
                <q-btn flat color="" :to="{name: 'IndexRule'}">Правила операций</q-btn>
                <q-btn flat color="" :to="{name: 'ContractorIndex'}">Контрагенты</q-btn>
                <q-btn flat color="" :to="{name: 'CategoriesIndex'}">Категории</q-btn>
              </q-toolbar-title>
<!--            <q-toolbar-title class="text-right">-->
<!--              <q-btn v-if="isAuth" flat text-color="white" color="grey-8" @click="logout">Выход</q-btn>-->
<!--            </q-toolbar-title>-->
          </q-toolbar>
      </q-header>
    <q-page-container>
      <router-view/>
    </q-page-container>

  </q-layout>
</template>

<style lang="sass">

</style>
