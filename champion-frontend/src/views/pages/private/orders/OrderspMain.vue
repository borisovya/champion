<script setup lang="ts">
import {onMounted, ref} from 'vue';
import ProgressBar from 'primevue/progressbar';
import ShopItemsTable from '@/components/shop/ShopItemsTable.vue';
import type {Product} from '@/types/Products';
import {getProductList} from '@/http/shop/ShopServices';
import OrderItemsTable from '@/components/orders/OrderItemsTable.vue';
import type {Order} from '@/types/Order';
import {Category} from '@/types/Category';

const orders = ref<Order[]>([]);
const loading = ref(false);

onMounted(async () => {
  loading.value = true;
  setTimeout(() => {
    orders.value = [
      {
        id: 1,
        orderNumber: 143321,
        items: [
          {
            id: 1,
            name: 'Product 1',
            description: '',
            price: 500,
            qty: 2,
            status: true,
            category: {
              id: 1,
              name: 'cat 1',
              status: true
            },
            image: ''
          },
          {
            id: 3,
            name: 'Product 3',
            description: '',
            price: 300,
            qty: 1,
            status: true,
            category: {
              id: 3,
              name: 'cat 3',
              status: true
            },
            image: ''
          }
          ],
        totalPrice: 800,
        status: true,
        createdAt: '16.032024',
        username: 'partner1@maail.ru',
        championPartnersLogin: 'partner1'
      },
      {
        id: 2,
        orderNumber: 244452,
        items: [
          {
            id: 2,
            name: 'Product 2',
            description: '',
            price: 100,
            qty: 4,
            status: true,
            category: {
              id: 2,
              name: 'cat 2',
              status: true
            },
            image: ''
          }],
        totalPrice: 100,
        status: false,
        createdAt: '16.032024',
        username: 'partner2@maail.ru',
        championPartnersLogin: 'partner2'
      }
    ];
    loading.value = false;
  }, 500);
});

</script>

<template>
  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <OrderItemsTable v-else :orders="orders" :isLoading="loading"/>
  </div>
</template>
