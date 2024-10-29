export default [
    {
        path:'/',
        component:() => import('@/pages/Operations.vue'),
        name:'Operations',
    },
    {
        path:'/categories',
        component:() => import('@/pages/Categories.vue'),
        name:'Categories',
    },
]
