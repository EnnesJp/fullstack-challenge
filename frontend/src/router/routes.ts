import { RouteRecordRaw } from 'vue-router'
import AuthenticatedLayout from 'layouts/AuthenticatedLayout.vue'
import GuestLayoutVue from 'layouts/GuestLayout.vue'

const routes: RouteRecordRaw[] = [
  {
    name: 'Login',
    path: '/login',
    component: () => GuestLayoutVue,
    children: [{ path: '', component: () => import('pages/Auth/Login.vue') }],
  },
  {
    name: 'Register',
    path: '/register',
    component: () => GuestLayoutVue,
    children: [
      { path: '', component: () => import('pages/Auth/Register.vue') },
    ],
  },
  {
    path: '/',
    component: () => AuthenticatedLayout,
    children: [{ path: '', component: () => import('pages/ExpensesDashboard.vue') }],
    meta: { requiresAuth: true },
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
