import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/pages/HomeView.vue'
import AppLayout from '@/layout/AppLayout.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: AppLayout,
      children:[
        {
          path: '/',
          component: HomeView
        }
      ]
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/pages/auth/Login.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/pages/auth/Register.vue')
    },
    {
      path: '/password-reset',
      name: 'password-reset',
      component: () => import('@/views/pages/auth/PasswordReset.vue')
    },
  ]
})

export default router
