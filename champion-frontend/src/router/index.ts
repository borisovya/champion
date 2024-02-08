import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from '@/layout/AppLayout.vue';
import Shop from '@/views/pages/private/shop/ShopMain.vue';
import Partners from '@/views/pages/private/partners/PartnersMain.vue';
import News from '@/views/pages/private/News.vue';
import PublicShop from '@/views/pages/public/PublicShop.vue';
import AdminProfile from '@/views/pages/private/AdminProfile.vue';
import Notification from '@/views/pages/private/Notifications.vue';
import PartnersShow from '@/views/pages/private/partners/PartnersShow.vue';
import NotFound from '@/views/pages/public/NotFound.vue';
import PartnerCreate from '@/views/pages/private/partners/PartnerCreate.vue';
import ShopCategories from '@/views/pages/private/shopCategories/ShopCategories.vue';
import ShopShow from '@/views/pages/private/shop/ShopShow.vue';
import ShopCreate from '@/views/pages/private/shop/ShopCreate.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      children:[
        {
          path: '/',
          component: PublicShop
        },
      ]
    },
    {
      path: '/admin',
      component: AppLayout,
      children:[
        {
          path: '/admin',
          component: Partners
        },
        {
          path: '/admin/partner/show/:id',
          component: PartnersShow
        },
        {
          path: '/admin/partner/create',
          component: PartnerCreate
        },
        {
          path: '/admin/shop',
          component: Shop
        },
        {
          path: '/admin/shop/create',
          component: ShopCreate
        },
        {
          path: '/admin/shop/show/:id',
          component: ShopShow
        },
        {
          path: '/admin/shop/categories',
          component: ShopCategories
        },
        {
          path: '/admin/news',
          component: News
        },
        {
          path: '/admin/notifications',
          component: Notification
        },
        {
          path: '/admin/profile',
          component: AdminProfile
        }
      ]
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/pages/public/auth/LoginPage.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/pages/public/auth/RegisterPage.vue')
    },
    {
      path: '/password-reset',
      name: 'password-reset',
      component: () => import('@/views/pages/public/auth/PasswordReset.vue')
    },
    {
      path: '/:path(.*)',
      component: NotFound,
    },
  ]
})

export default router
