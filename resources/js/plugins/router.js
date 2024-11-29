import {createRouter, createWebHistory} from 'vue-router'
import routes from '@/routes'
import {handleAdminAccess} from "./middleware.js";
import {useUserStore} from "./store/users.js";
import {inject} from "vue";

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    const user_id = inject('user_id')
    const user = inject('user')
    // const userStore = useUserStore()
    // const user = userStore.getUser

    if (user?.id) {
        next();
    } else {
        next('/login');
    }
});

export default router
