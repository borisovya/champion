<script setup lang="ts">
import { onMounted, ref } from 'vue'
import ProgressBar from 'primevue/progressbar'
import ShopItemsTable from '@/components/shop/ShopItemsTable.vue'
import type { Product } from '@/types/Products'
import { getProductList } from '@/http/shop/ShopServices'

const products = ref<Product[]>([])
const loading = ref(false)

onMounted(async () => {
  loading.value = true
  try {
    const res = await getProductList()
    products.value = [...res].sort((a, b) => b.id - a.id)
  } catch (e) {
    console.log(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <ShopItemsTable v-else :products="products" :isLoading="loading" />
  </div>
</template>
