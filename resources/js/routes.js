export default [
    {
        path:'/login',
        component:() => import('@/pages/Auth/Login.vue'),
        name:'Login',
        meta: { guest: true }
    },
    {
        path:'/',
        component:() => import('@/pages/Operations/Index.vue'),
        name:'OperationIndex',
        meta: { requiresAuth: true }
    },
    {
        path:'/operations/create',
        component:() => import('@/pages/Operations/Create.vue'),
        name:'OperationCreate',
        meta: { requiresAuth: true }
    },
    {
        path:'/operations/edit/:id',
        component:() => import('@/pages/Operations/Edit.vue'),
        name:'OperationEdit',
        meta: { requiresAuth: true }
    },
    {
        path:'/operations/rules/create',
        component:() => import('@/pages/OperationRules/Create.vue'),
        name:'CreateRule',
        meta: { requiresAuth: true }
    },
    {
        path:'/operations/rules',
        component:() => import('@/pages/OperationRules/Index.vue'),
        name:'IndexRule',
        meta: { requiresAuth: true }
    },
    {
        path:'/operations/rules/edit/:id',
        component:() => import('@/pages/OperationRules/Edit.vue'),
        name:'EditRule',
        meta: { requiresAuth: true }
    },
    {
        path:'/operations/summary',
        component:() => import('@/pages/Operations/IndexSummary.vue'),
        name:'IndexSummary',
        meta: { requiresAuth: true }
    },
    {
        path:'/categories',
        component:() => import('@/pages/Categories/Index.vue'),
        name:'CategoriesIndex',
        meta: { requiresAuth: true }
    },
    {
        path:'/categories/edit/:id',
        component:() => import('@/pages/Categories/Edit.vue'),
        name:'CategoriesEdit',
        meta: { requiresAuth: true }
    },
    {
        path:'/categories/:id/operations',
        component:() => import('@/pages/Categories/CategoryOperations.vue'),
        name:'CategoryOperations',
        meta: { requiresAuth: true }
    },
    {
        path:'/categories/create',
        component:() => import('@/pages/Categories/Create.vue'),
        name:'CategoriesCreate',
        meta: { requiresAuth: true }
    },
    {
        path:'/categories-tree',
        component:() => import('@/pages/Categories/IndexTree.vue'),
        name:'CategoriesTree',
        meta: { requiresAuth: true }
    },
    {
        path:'/contractors',
        component:() => import('@/pages/Contractors/Index.vue'),
        name:'ContractorIndex',
        meta: { requiresAuth: true }
    },
    {
        path:'/contractors/:id/rules',
        component:() => import('@/pages/Contractors/Rules.vue'),
        name:'ContractorRules',
        meta: { requiresAuth: true }
    },

    {
        path:'/contractors/:id/operations',
        component:() => import('@/pages/Contractors/Operations.vue'),
        name:'ContractorOperations',
        meta: { requiresAuth: true }
    },
    {
        path:'/contractors/:id',
        component:() => import('@/pages/Contractors/Show.vue'),
        name:'ContractorShow',
        meta: { requiresAuth: true }
    },
]
