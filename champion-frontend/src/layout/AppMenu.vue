<script setup>
import { ref } from 'vue'
import AppMenuItem from './AppMenuItem.vue'

const model = ref([
  {
    items: [
      { label: 'Партнеры', icon: 'pi pi-users', to: '/admin' },
      { label: 'Витрина', icon: 'pi pi-shopping-bag', to: '/admin/shop' },
      { label: 'Категории товаров', icon: 'pi pi-sliders-h', to: '/admin/shop/categories' },
      { label: 'Новости', icon: 'pi pi-list', to: '/admin/news' },
      { label: 'Рассылка', icon: 'pi pi-send', to: '/admin/notifications' }
    ]
  },
  {
    label: 'V------Не будет в сайд баре-------V',
    items: [
      { label: 'Login', icon: 'pi pi-user', to: '/login' },
      { label: 'Registration', icon: 'pi pi-user-plus', to: '/register' },
      { label: 'PasswordReset', icon: 'pi  pi-key', to: '/password-reset' }
    ]
  }
])
</script>

<template>
  <ul class="layout-menu">
    <template v-for="(item, i) in model" :key="item">
      <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
      <li v-if="item.separator" class="menu-separator"></li>
    </template>
  </ul>
</template>

<style lang="scss" scoped></style>
