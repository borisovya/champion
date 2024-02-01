<script setup>
import {ref} from 'vue';
import AppMenuItem from './AppMenuItem.vue';

const model = ref([
  {
    items: [
      {label: 'Партнеры', icon: 'pi pi-fw pi-users', to: '/admin'},
      {label: 'Витрина', icon: 'pi pi-fw pi-shopping-bag', to: '/admin/shop'},
      {label: 'Новости', icon: 'pi pi-fw pi-list', to: '/admin/news'},
      {label: 'Login', icon: 'pi pi-fw pi-user', to: '/login'},
      {label: 'Registration', icon: 'pi pi-fw pi-user-plus', to: '/register'},
      {label: 'PasswordReset', icon: 'pi pi-fw pi-key', to: '/password-reset'},
    ],
  },
]);

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
