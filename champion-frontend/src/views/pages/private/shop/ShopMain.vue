<script setup lang="ts">
import { onMounted, ref } from 'vue'
import ProgressBar from 'primevue/progressbar'
import ShopItemsTable from '@/components/shop/ShopItemsTable.vue'
import type { Product } from '@/types/Products'

const products = ref<Product[]>([])
const loading = ref(false)

onMounted(() => {
  loading.value = true
  setTimeout(() => {
    products.value = [
      {
        id: 1,
        name: 'Товар 1',
        description: 'Описание товара 1 Описание товара 1 Описание товара 1',
        price: 500,
        active: true,
        categoryId: 1,
        imgUrl:
          'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRB6p_1WriDjdY2v6Y5RXYkVlmtqAMAVIOBTw&usqp=CAU'
      },
      {
        id: 2,
        name: 'Товар 2',
        description: 'Описание товара 2 товара 2 Описание товара 2',
        price: 2500,
        active: true,
        categoryId: 2,
        imgUrl:
          'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGbT5FI9ZoXAAVDVluVs1hbO5oCMUyMU1w2g&usqp=CAU'
      },
      {
        id: 3,
        name: 'Товар 3',
        description: 'Описание товара 3 Описание товара 3 Описание товара 3',
        price: 300,
        active: true,
        categoryId: 1,
        imgUrl:
          'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR7SoYsXmu_-uMQnJ7PPr6v9ND3jrq3oYri-g&usqp=CAU'
      },
      {
        id: 4,
        name: 'Товар 4',
        description: 'Описание товара 4 Описание товара 4 Описание товара 4',
        price: 15500,
        active: true,
        categoryId: 3,
        imgUrl:
          'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSkUso7lKSjoBcBh9-vKqQQQxW7nfzBF08Bug&usqp=CAU'
      },
      {
        id: 5,
        name: 'Товар 5',
        description: 'Описание товара 5 Описание товара 5 Описание товара 5',
        price: 9500,
        active: false,
        categoryId: 4,
        imgUrl:
          'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRwXHQ8nJw9on_IdjF5c59hjx4ZdHVqP9sw0w&usqp=CAU'
      }
    ]
    loading.value = false
  }, 500)
})
</script>

<template>
  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ShopItemsTable v-if="!loading" :products="products" :isLoading="loading" />

    <ProgressBar v-else mode="indeterminate" style="height: 6px"></ProgressBar>
  </div>
</template>
