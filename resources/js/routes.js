export default [
    {
        path:'/',
        component:() => import('@/pages/Operations/Index.vue'),
        name:'OperationIndex',
    },
    {
        path:'/operations/create',
        component:() => import('@/pages/Operations/Create.vue'),
        name:'OperationCreate',
    },
    {
        path:'/categories',
        component:() => import('@/pages/Categories.vue'),
        name:'Categories',
    },
]
