import Vue from 'vue'
import Router from 'vue-router'
import Login from '../views/auth/Login'
import Dashboard from '../views/dashboard/Dashboard'
import User from '../views/user/User'
import Role from '../views/role/Role.vue'
import SimpleCrud from '../views/simple-crud/SimpleCrud.vue'

Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            alias: '/login',
            component: Login,
            name: 'login',
            meta: {
                title: 'Login - Rental',
                description: 'Login panel',
                requiresAuth: false,

            }
        },
        {
            path: '/dashboard',
            component: Dashboard,
            name: 'Dashboard',
            meta: {
                title: 'Dashboard',
                description: 'Dashboard module',
                requiresAuth: true,
            }
        },
        {
            path: '/users',
            component: User,
            name: 'User',
            meta: {
                title: 'User',
                description: 'User module',
                requiresAuth: true,
            }
        },
        {
            path: '/roles',
            component: Role,
            name: 'Role',
            meta: {
                title: 'Role',
                description: 'Role module',
                requiresAuth: true,
            }
        },
        {
            path: '/simple-crud',
            component: SimpleCrud,
            name: 'SimpleCrud',
            meta: {
                title: 'SimpleCrud',
                description: 'Simple crud module',
                requiresAuth: true,
            }
        }
    ],
})