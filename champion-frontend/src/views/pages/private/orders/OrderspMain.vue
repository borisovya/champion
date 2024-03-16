<script setup lang="ts">
import { onMounted, ref } from 'vue'
import ProgressBar from 'primevue/progressbar'
import ShopItemsTable from '@/components/shop/ShopItemsTable.vue'
import type { Product } from '@/types/Products'
import { getProductList } from '@/http/shop/ShopServices'
import OrderItemsTable from '@/components/orders/OrderItemsTable.vue';
import type {Order} from '@/types/Order';
import {Category} from '@/types/Category';

const orders = ref<Order[]>([])
const loading = ref(false)

onMounted(async () => {
  loading.value = true
  setTimeout(()=>{
    orders.value = [
      {
        id: 1,
        orderNumber: 1,
        items: [{
          id: 1,
          name: 'Product 1',
          description: '',
          price: 500,
          status: true,
          category: {
            id: 1,
            name: 'cat 1',
            status: true
          },
          image: ''
        }],
        totalPrice: 500,
        status: true,
        createdAt: '16.032024'
      },
      {
        id: 2,
        orderNumber: 2,
        items: [{
          id: 2,
          name: 'Product 2',
          description: '',
          price: 100,
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
        createdAt: '16.032024'
      }
    ]
    loading.value = false
  }, 500)
})

console.log(loading.value);
</script>

<template>
  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <OrderItemsTable v-else :orders="orders" :isLoading="loading" />
  </div>
</template>
