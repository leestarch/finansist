import '@/bootstrap.js';
import {createApp} from 'vue'
import App from '@/App.vue'
import pinia from '@/plugins/pinia'
import router from '@/plugins/router'
import {quasarConfig, quasarInstance} from '@/plugins/quasar'
import '@/../styles/app.sass'

let appDiv = document.getElementById('app')
let props = JSON.parse(appDiv.innerHTML)
const auth = true

createApp(
    App,
    props
)
    .use(pinia)
    .provide('user_id', props.user_id)
    .provide('user', props.user)
    .use(router)
    .provide('auth', auth)
    .use(quasarInstance, quasarConfig)
    .mount('#app')
