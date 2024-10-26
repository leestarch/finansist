<script setup>
import {onMounted, ref, provide, computed} from "vue";
import {useQuasar} from "quasar";
import {useUserStore} from "./plugins/store/users.js";

const props = defineProps(['user_id', 'is_worker'])
const store = useUserStore()
const user = computed(() => {
    return store.user;
});

const $q = useQuasar()
const drawer = ref(false)
onMounted(async () => {
    provide('user', user)
})
</script>

<template>
  <q-layout view="hHh lpR fFf">
      <q-header elevated class="bg-primary text-white">
          <q-toolbar>
              <q-btn  flat @click="drawer = !drawer" round dense icon="menu" />
              <q-toolbar-title>MMS</q-toolbar-title>
          </q-toolbar>
      </q-header>
      <q-drawer
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
                  <q-item  v-ripple>
                      <q-item-section>
                          <q-btn flat color="grey-8" :to="{name: 'Welcome'}">Home </q-btn>
                      </q-item-section>
                  </q-item>
                  <q-item  v-ripple>
                      <q-item-section>
                          <q-btn flat color="grey-8" :to="{name: 'Welcome2'}">Home2</q-btn>
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
