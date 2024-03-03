<script setup lang="ts">
import { onMounted, ref } from 'vue'
import ProgressBar from 'primevue/progressbar'
import type { News } from '@/types/News'
import NewsTable from '@/components/news/NewsTable.vue'
import { getNewsList } from '@/http/news/NewsServices'

const news = ref<News[]>([])
const loading = ref(false)

onMounted(async () => {
  loading.value = true
  try {
    const res = await getNewsList()
    news.value = [...res].sort((a, b) => b.id - a.id)
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

    <NewsTable v-else :news="news" :isLoading="loading" />
  </div>
</template>
