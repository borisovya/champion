import {createRouter, createWebHistory} from 'vue-router';
import AppLayout from '@/layout/AppLayout.vue'
import Shop from '@/views/pages/private/shop/ShopMain.vue'
import Partners from '@/views/pages/private/users/UserMain.vue'
import PublicShop from '@/views/pages/public/PublicShop.vue'
import Notification from '@/views/pages/private/notifications/NotificationsMain.vue'
import PartnersShow from '@/views/pages/private/users/UserShow.vue'
import NotFound from '@/views/pages/public/NotFound.vue'
import PartnerCreate from '@/views/pages/private/users/UserCreate.vue'
import ShopCategories from '@/views/pages/private/shopCategories/ShopCategoriesMain.vue'
import ShopShow from '@/views/pages/private/shop/ShopShow.vue'
import ShopCreate from '@/views/pages/private/shop/ShopCreate.vue'
import ShopCategoryCreate from '@/views/pages/private/shopCategories/ShopCategoryCreate.vue'
import NewsMain from '@/views/pages/private/news/NewsMain.vue'
import NewsCreate from '@/views/pages/private/news/NewsCreate.vue'
import NewsShow from '@/views/pages/private/news/NewsShow.vue'
import { useUserStore } from '@/store/useStore'
import { getUserFromToken, refreshToken } from '@/helpers/TokenHelper'
import OrderspMain from '@/views/pages/private/orders/OrderspMain.vue';
import {getFromCookie} from '@/helpers/CookieHelper';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      children: [
        {
          path: '',
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
        requiresAuth: true,
        role: 'admin'
      },
      children: [
        {
          path: '',
          name: 'admin-index',
          component: Partners
        },
        {
          path: 'partner/show/:id',
          component: PartnersShow
        },
        {
          path: 'partner/create',
          component: PartnerCreate
        },
        {
          path: 'shop',
          component: Shop
        },
        {
          path: 'shop/create',
          component: ShopCreate
        },
        {
          path: 'shop/show/:id',
          component: ShopShow
        },
        {
          path: 'shop/categories',
          component: ShopCategories
        },
        {
          path: 'shop/category/create',
          component: ShopCategoryCreate
        },
        {
          path: 'orders',
          component: OrderspMain
        },
        {
          path: 'news',
          component: NewsMain
        },
        {
          path: 'news/create',
          component: NewsCreate
        },
        {
          path: 'news/show/:id',
          component: NewsShow
        },
        {
          path: 'notifications',
          component: Notification
        }
      ]
    },
    {
      path: '/login',
      title: 'login',
      name: 'login',
      component: () => import('@/views/pages/public/auth/LoginPage.vue'),
      meta: {
        requiresNotAuth: true
      }
    },
    {
      path: '/register',
      title: 'register',
      component: () => import('@/views/pages/public/auth/RegisterPage.vue'),
      meta: {
        requiresNotAuth: true
      }
    },
    {
      path: '/password-reset',
      title: 'password-reset',
      component: () => import('@/views/pages/public/auth/PasswordReset.vue'),
      meta: {
        requiresNotAuth: true
      }
    },
    {
      path: '/:path(.*)',
      component: NotFound
    }
  ]
})

router.beforeEach(async (to, from) => {
  const user = useUserStore().getUser()
  const token = getFromCookie('access_token')
  const userData = token && JSON.parse(atob(token.split('.')[1]))

  if (to.meta.requiresNotAuth && user) {
    return { name: 'admin-index' }
  }

  if (to.meta.requiresAuth && !user) {
    try {
      await refreshToken()

      useUserStore().setUser(getUserFromToken())

      return to
    } catch {
      return { name: 'login' }
    }
  }

  if(to.meta.role === 'admin' && token && userData.roles[0] === 'ROLE_USER'){
    return { name: '' }
  }

  if ((to.name === 'login' || to.name === 'register') && token) {
    router.back()
  }
})

export default router
