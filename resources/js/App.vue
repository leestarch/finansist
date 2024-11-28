<script setup>
import {onMounted, ref, provide, computed} from "vue";
import {useQuasar} from "quasar";
import {useUserStore} from "./plugins/store/users.js";
import router from "./plugins/router.js";

const props = defineProps(['user_id', 'is_worker'])
const store = useUserStore()
const user = computed(() => {
    return store.user;
});
const isAuth = ref(localStorage.getItem('token') !== null);

const $q = useQuasar()
const drawer = ref(false)
onMounted(async () => {
    provide('user', user)
})

const logout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    $q.notify({
        color: 'positive',
        position: 'top',
        message: 'Вы вышли из системы',
        icon: 'check'
    });
    isAuth.value = false;
    router.push('/login');
}
</script>

<template>
  <q-layout view="hHh lpR fFf">
      <q-header elevated class="bg-primary text-white">
          <q-toolbar>
              <q-btn  flat @click="drawer = !drawer" round dense icon="menu" />
              <q-toolbar-title>
                <q-btn v-if="isAuth" flat text-color="white" color="grey-8" :to="{name: 'OperationIndex'}">Finansist</q-btn>
              </q-toolbar-title>
            <q-toolbar-title class="text-right">
              <q-btn v-if="isAuth" flat text-color="white" color="grey-8" @click="logout">Выход</q-btn>
            </q-toolbar-title>
          </q-toolbar>
      </q-header>
      <q-drawer
          v-if="isAuth"
          v-model="drawer"
          show-if-above
          :width="200"
          :breakpoint="500"
          bordered
          overlay
          class="bg-grey-2 "
      >
          <q-scroll-area class="fit">
              <q-list padding class="menu-list">
<!--                  <q-item  v-ripple>-->
<!--                      <q-item-section>-->
<!--                          <q-btn flat color="grey-8" :to="{name: 'Categories'}">Категории</q-btn>-->
<!--                      </q-item-section>-->
<!--                  </q-item>-->
                <q-item  v-ripple>
                  <q-item-section>
                    <q-btn flat color="grey-8" :to="{name: 'CategoriesTree'}">Дерево категорий</q-btn>
                  </q-item-section>
                </q-item>
<!--                <q-item  v-ripple>-->
<!--                  <q-item-section>-->
<!--                    <q-btn flat color="grey-8" :to="{name: 'IndexSummary'}">Summary</q-btn>-->
<!--                  </q-item-section>-->
<!--                </q-item>-->
                <q-item  v-ripple>
                  <q-item-section>
                    <q-btn flat color="grey-8" :to="{name: 'IndexRule'}">Правила операций</q-btn>
                  </q-item-section>
                </q-item>
                <q-item  v-ripple>
                  <q-item-section>
                    <q-btn flat color="grey-8" :to="{name: 'ContractorIndex'}">Контрагенты</q-btn>
                  </q-item-section>
                </q-item>
                <q-item  v-ripple>
                  <q-item-section>
                    <q-btn flat color="grey-8" :to="{name: 'CategoriesIndex'}">Категории</q-btn>
                  </q-item-section>
                </q-item>
              </q-list>
          </q-scroll-area>
      </q-drawer>

    <q-page-container>
      <router-view/>
    </q-page-container>

  </q-layout>
</template>

<style lang="sass">

</style>
