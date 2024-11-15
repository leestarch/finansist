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
        path:'/operations/summary',
        component:() => import('@/pages/Operations/IndexSummary.vue'),
        name:'IndexSummary',
    },
    {
        path:'/categories',
        component:() => import('@/pages/Categories/IndexDepr.vue'),
        name:'IndexDepr',
    },
    {
        path:'/categories/rule',
        component:() => import('@/pages/Categories/CreateRule.vue'),
        name:'CreateRule',
    },
    {
        path:'/categories-tree',
        component:() => import('@/pages/Categories/IndexTree.vue'),
        name:'CategoriesTree',
    },
]
