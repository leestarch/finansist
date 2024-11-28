<script setup>

import {ref} from "vue";
import {Loading} from "quasar";
import router from "../../plugins/router.js";

const data = ref({
  email: '',
  password: ''
})

const submit = async ()  => {
  Loading.show();
  try{
    const response = await axios.post('/api/login', data.value);
    if(response.data.success){
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
      await router.push('/');
    }
  }catch (e) {
    console.log(e.response.data.message);
  }
  Loading.hide();
}

</script>

<template>
  <q-page class="flex flex-center">
    <q-card class="q-pa-md" style="max-width: 600px; min-width: 400px">
      <q-card-section>
        <div class="text-h6 text-center">Вход</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit.prevent="submit">
          <q-input
              v-model="data.email"
              label="Username"
              outlined
              required
              class="q-mb-md"
          />
          <q-input
              v-model="data.password"
              label="Password"
              type="password"
              outlined
              required
              class="q-mb-md"
          />
          <q-btn type="submit" label="Войти" color="primary" class="full-width" />
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<style scoped>

</style>