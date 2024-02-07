<script setup lang="ts">
import PartnersTable from '@/components/PartnersTable.vue';
import {onMounted, ref} from 'vue';
import type {Partner} from '@/types/Partner';

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
        bonusBalance: 1000
      },
      {
        id: 2,
        name: 'Ivan2',
        email: 'eeeee2.mail.ru',
        telegram: '@ivan2',
        championId: 2,
        bonusBalance: 2000
      },
      {
        id: 3,
        name: 'Ivan3',
        email: 'eeeee3.mail.ru',
        telegram: '@ivan3',
        championId: 3,
        bonusBalance: 3000
      },
    ];
    loading.value = false
  }, 2000);
});
</script>

<template>
  <PartnersTable :partnersData="partners" :isLoading="loading"/>
</template>
