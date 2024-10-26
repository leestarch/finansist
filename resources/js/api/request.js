window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

alert(import.meta.env.MIX_API_URL)

const apiClient = axios.create(
    {
        baseURL: import.meta.env.MIX_API_URL,
        withCredentials: true,
        timeout: 60000 * 5,
    }
)

apiClient.interceptors.response.use(
    response => {
        return response.data;
    },
    error => {
        let message = error.message;
        if (error.response.data && error.response.data.errors) {
            message = error.response.data.errors;
        } else if (error.response.data && error.response.data.error) {
            message = error.response.data.error;
        }
        if (message === 'Request failed with status code 401') {
            message = 'Нет доступа, пожалуста авторизуйтесь <br><b><a href=\'/login\'>Перейти</a></b>';
            console.log(message)
        } else {
            console.log(message)
        }
        return Promise.reject(error);
    }
);

export default apiClient;
