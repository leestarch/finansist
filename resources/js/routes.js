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
        path:'/operations/rule',
        component:() => import('@/pages/Operations/CreateRule.vue'),
        name:'CreateRule',
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
        path:'/categories-tree',
        component:() => import('@/pages/Categories/IndexTree.vue'),
        name:'CategoriesTree',
    },
    {
        path:'/contractors',
        component:() => import('@/pages/Contractors/Index.vue'),
        name:'ContractorIndex',
    },
]
