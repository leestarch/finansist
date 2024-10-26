// import { useAuthStore } from '@/composables/auth'
import { Notify } from 'quasar'

import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.post['Content-Type'] = 'application/json';

window.axios.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    console.log(error)

    // const auth = useAuthStore()

    // if (error.response?.status === 401) auth.toLogin();

    Notify.create({
      type: "negative",
      message: `<strong>Ошибка: ${error.response?.status}</strong><br>${error.response?.data?.message}`,
      html: true,
    });

    return Promise.reject(error)
  }
);
