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
        path:'/operations/rules/create',
        component:() => import('@/pages/OperationRules/Create.vue'),
        name:'CreateRule',
    },
    {
        path:'/operations/rules',
        component:() => import('@/pages/OperationRules/Index.vue'),
        name:'IndexRule',
    },
    {
        path:'/operations/rules/edit/:id',
        component:() => import('@/pages/OperationRules/Edit.vue'),
        name:'EditRule',
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
    {
        path:'/contractors/:id/rules',
        component:() => import('@/pages/Contractors/Rules.vue'),
        name:'ContractorRules',
    },
    {
        path:'/contractors/:id/operations',
        component:() => import('@/pages/Contractors/Operations.vue'),
        name:'ContractorOperations',
    },
    {
        path:'/contractors/:id',
        component:() => import('@/pages/Contractors/Show.vue'),
        name:'ContractorShow',
    },
]
