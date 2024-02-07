<script setup lang="ts">
import PartnersTable from '@/components/PartnersTable.vue';
import {onMounted, ref} from 'vue';
import type {Partner} from '@/types/Partner';
import ProgressBar from 'primevue/progressbar';

const partners = ref<Partner[]>([]);
const loading = ref(false);

onMounted(() => {
  loading.value = true;
  setTimeout(() => {
    partners.value = [
      {
        id: 1,
        name: 'Ivan',
        email: 'eeeee.mail.ru',
        telegram: '@ivan',
        championId: 1,
        championLogin: '123fff',
        bonusBalance: 1000
      },
      {
        id: 2,
        name: 'Ivan2',
        email: 'eeeee2.mail.ru',
        telegram: '@ivan2',
        championId: 2,
        championLogin: '123fff',
        bonusBalance: 2000
      },
      {
        id: 3,
        name: 'Ivan3',
        email: 'eeeee3.mail.ru',
        telegram: '@ivan3',
        championId: 3,
        championLogin: '123fff',
        bonusBalance: 3000
      },
      {
        id: 4,
        name: 'Iv4',
        email: 'eeeee344.mail.ru',
        telegram: '@iv4',
        championId: 4,
        championLogin: '12444',
        bonusBalance: 1000
      },
    ];
    loading.value = false;
  }, 1000);
});
</script>

<template>
  <div class="card " style="height: 80vh; overflow: auto">
    <PartnersTable v-if="!loading" :partnersData="partners" :isLoading="loading"/>

    <ProgressBar v-else mode="indeterminate" style="height: 6px"></ProgressBar>
  </div>

</template>
