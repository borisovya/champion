<script setup lang="ts">
import { onMounted, ref } from 'vue'
import ProgressBar from 'primevue/progressbar'
import type { Category } from '@/types/Category'
import CategoriesTable from '@/components/categories/CategoriesTable.vue'
import { getCategories } from '@/http/categories/CategoriesServices'

const categories = ref<Category[]>([])
const loading = ref(false)

onMounted(async () => {
  loading.value = true
  try {
    const res = await getCategories()
    categories.value = categories.value = [...res].sort((a, b) => b.id - a.id)
  } catch (e) {
    console.log(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="card cardContainer" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <CategoriesTable v-else :categories="categories" />
  </div>
</template>
