import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/auth';
function isAuthenticated() {
    return useAuthStore().isAuthenticated
}
const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'landingPage', component: ()=> import('../pages/auth/Login.vue'),meta: { layout: 'basic' }},
        { path: '/login', name: 'login', component: ()=> import('../pages/auth/Login.vue'),meta: { layout: 'basic'}},
        { path: '/register', name: 'register', component: ()=> import('../pages/auth/Register.vue'),meta: { layout: 'basic'}},
        { path: '/dashboard', name: 'dashboard', component: ()=> import('../pages/dashboard.vue'),meta: { requiresAuth: true }},
        { path: '/companies', name: 'companies', component: ()=> import('../pages/companies/listCompanies.vue'),meta: { requiresAuth: true }},
        { path: '/addcompanies', name: 'addCompanies', component: ()=> import('../pages/companies/addCompanies.vue'),meta: { requiresAuth: true }},
        { path: '/updatecompanies/:id', name: 'updateCompanies', component: ()=> import('../pages/companies/updateCompanies.vue'),meta: { requiresAuth: true }},
        { path: '/viewcompanies/:id', name: 'viewCompanies', component: ()=> import('../pages/companies/viewCompanies.vue'),meta: { requiresAuth: true }},

        { path: '/employees', name: 'employees', component: ()=> import('../pages/employees/listEmployees.vue'),meta: { requiresAuth: true }},
        { path: '/addemployees', name: 'addEmployees', component: ()=> import('../pages/employees/addEmployees.vue'),meta: { requiresAuth: true }},
        { path: '/updateemployees/:id', name: 'updateEmployees', component: ()=> import('../pages/employees/updateEmployees.vue'),meta: { requiresAuth: true }},
        { path: '/viewemployees/:id', name: 'viewEmployees', component: ()=> import('../pages/employees/viewEmployees.vue'),meta: { requiresAuth: true }},

        {
            path: '/:catchAll(.*)',
            redirect: (to) => {
                if (to.meta.requiresAuth && !isAuthenticated()) {
                    return { name: 'login' };
                }
            },
        }
    ],

});
router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !isAuthenticated()) {
        next({ name: 'login' });
    } else {
        next();
    }
});
export default router;
