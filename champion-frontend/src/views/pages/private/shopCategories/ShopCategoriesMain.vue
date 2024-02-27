<script setup lang="ts">
import { onMounted, ref } from 'vue'
import ProgressBar from 'primevue/progressbar'
import type { Category } from '@/types/Category'
import CategoriesTable from '@/components/categories/CategoriesTable.vue'
import {getCategories} from '@/http/categories/CategoriesServices';

const categories = ref<Category[]>([])
const loading = ref(false)

onMounted(async () => {
  loading.value = true
  try {
    const categories = await getCategories()
    console.log(categories);
  } catch (e) {
    console.log(e);
  }

  setTimeout(() => {
    categories.value = [
      {
        id: 1,
        name: 'Электроника',
        active: true
      },
      {
        id: 2,
        name: 'Одежда',
        active: true
      },
      {
        id: 3,
        name: 'Транспорт',
        active: true
      },
      {
        id: 4,
        name: 'Аксесуары',
        active: false
      }
    ]
    loading.value = false
  }, 500)
})
</script>

<template>
  <div class="card cardContainer" style="height: calc(100vh - 9rem); overflow: auto">
    <CategoriesTable v-if="!loading" :categories="categories" :isLoading="loading" />

    <ProgressBar v-else mode="indeterminate" style="height: 6px"></ProgressBar>
  </div>
</template>
