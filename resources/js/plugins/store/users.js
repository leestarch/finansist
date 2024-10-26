import {defineStore} from 'pinia'

export const useUserStore = defineStore("user",{
    state: () => ({
        user: [],
    }),
    getters: {
        getUser(state){
            return state.user
        }
    },
    actions: {
        async loadUser(user_id) {
             try {
                 const res = await fetch(`https://dyatlovait.ru/api/users/${user_id}`);
                 this.user = await res.json()
             } catch (error) {
                 console.error('Error loading user:', error);
             }
        }
    },
})
