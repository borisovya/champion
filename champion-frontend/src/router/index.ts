import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from '@/layout/AppLayout.vue'
import Shop from '@/views/pages/private/shop/ShopMain.vue'
import Partners from '@/views/pages/private/partners/PartnersMain.vue'
import PublicShop from '@/views/pages/public/PublicShop.vue'
import Notification from '@/views/pages/private/notifications/NotificationsMain.vue'
import PartnersShow from '@/views/pages/private/partners/PartnersShow.vue'
import NotFound from '@/views/pages/public/NotFound.vue'
import PartnerCreate from '@/views/pages/private/partners/PartnerCreate.vue'
import ShopCategories from '@/views/pages/private/shopCategories/ShopCategoriesMain.vue'
import ShopShow from '@/views/pages/private/shop/ShopShow.vue'
import ShopCreate from '@/views/pages/private/shop/ShopCreate.vue'
import ShopCategoryCreate from '@/views/pages/private/shopCategories/ShopCategoryCreate.vue'
import NewsMain from '@/views/pages/private/news/NewsMain.vue'
import NewsCreate from '@/views/pages/private/news/NewsCreate.vue'
import NewsShow from '@/views/pages/private/news/NewsShow.vue'
import { getFromCookie } from '@/helpers/CookieHelper'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      children: [
        {
          path: '/',
          component: PublicShop,
          meta: {
            requiresAuth: true
          }
        }
      ]
    },
    {
      path: '/admin',
      component: AppLayout,
      meta: {
        requiresAuth: true
      },
      children: [
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
          path: '/admin/shop/category/create',
          component: ShopCategoryCreate
        },
        {
          path: '/admin/news',
          component: NewsMain
        },
        {
          path: '/admin/news/create',
          component: NewsCreate
        },
        {
          path: '/admin/news/show/:id',
          component: NewsShow
        },
        {
          path: '/admin/notifications',
          component: Notification
        }
      ]
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/pages/public/auth/LoginPage.vue'),
      beforeEnter: (to, from, next) => {
        const user = getFromCookie('token')
        if (user) {
          next('/')
        } else {
          next()
        }
      }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/pages/public/auth/RegisterPage.vue'),
      beforeEnter: (to, from, next) => {
        const user = getFromCookie('token')
        if (user) {
          next('/')
        } else {
          next()
        }
      }
    },
    {
      path: '/password-reset',
      name: 'password-reset',
      component: () => import('@/views/pages/public/auth/PasswordReset.vue')
    },
    {
      path: '/:path(.*)',
      component: NotFound
    }
  ]
})

router.beforeEach(async (to, from) => {
  const user = getFromCookie('token')
  if (to.meta.requiresAuth && !user) {
    // await user.getUser();
    try {
      if (to.meta.requiresAuth) {
        return {
          path: '/login',
          // save the location we were at to come back later
          query: { redirect: to.fullPath }
        }
      }
    } catch {
      return {
        path: '/login',
        // save the location we were at to come back later
        query: { redirect: to.fullPath }
      }
    }
  }
})

export default router
