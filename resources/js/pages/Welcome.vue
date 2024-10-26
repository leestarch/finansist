<script setup>
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
const route = useRoute()
const hello = ref('')

const refresh = async (p) => {
  try{
    const response = await axios.get('/api')
    hello.value = response.data.message
  }catch (e) {
    console.log(e)
  }

}

onMounted(() => {
  if (route.name == 'Welcome')
    refresh()
})
</script>
<template>
  <q-card class="container" v-if="hello">
    <div class="q-pa-lg">
      {{hello}}
    </div>
  </q-card>
</template>

<style scoped>
</style>
