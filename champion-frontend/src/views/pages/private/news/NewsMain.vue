<script setup lang="ts">
import {onMounted, ref} from 'vue';
import ProgressBar from 'primevue/progressbar';
import type {News} from '@/types/News';
import NewsTable from '@/components/news/NewsTable.vue';

const news = ref<News[]>([]);
const loading = ref(false);

onMounted(() => {
  loading.value = true;
  setTimeout(() => {
    news.value = [
      {
        id: 1,
        title: 'Новсть 1 Новсть 1 Новсть 1 Новсть 1 Новсть 1',
        text: 'Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст',
        newsImg: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSAO3VamUZJCAXcGJHTyJLWKb0NvMTbO-dcg&usqp=CAU'
      },
      {
        id: 2,
        title: 'Новсть 2',
        text: 'Новсть 2 текст Новсть 2 текст Новсть 2 текст Новсть 2 текст Новсть 2 текст Новсть 2 текст Новсть 2 текст Новсть 2 текст Новсть 2 текст',
        newsImg: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSAO3VamUZJCAXcGJHTyJLWKb0NvMTbO-dcg&usqp=CAU'
      },
      {
        id: 3,
        title: 'Новсть 3',
        text: 'Новсть 3 текст Новсть 3 текст Новсть 3 текст Новсть 3 текст Новсть 3 текст',
        newsImg: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSAO3VamUZJCAXcGJHTyJLWKb0NvMTbO-dcg&usqp=CAU'
      },
      {
        id: 4,
        title: 'Новсть 4',
        text: 'Новсть 4 текст Новсть 4 текст Новсть 4 текст Новсть 4 текст',
        newsImg: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSAO3VamUZJCAXcGJHTyJLWKb0NvMTbO-dcg&usqp=CAU'
      },
    ];
    loading.value = false;
  }, 500);
});
</script>

<template>
  <div class="card cardContainer" style="height: calc(100vh - 9rem); overflow: auto;">
    <NewsTable v-if="!loading && news" :news="news" :isLoading="loading"/>

    <ProgressBar v-else mode="indeterminate" style="height: 6px"></ProgressBar>
  </div>
</template>

