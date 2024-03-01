<script setup lang="ts">
import PartnersTable from '@/components/partners/PartnersTable.vue'
import { onMounted, ref } from 'vue'
import type { Partner } from '@/types/Partner'
import ProgressBar from 'primevue/progressbar'
import { getCategories } from '@/http/categories/CategoriesServices'
import { getPartnerList } from '@/http/partners/PartnersServices'

const partners = ref<Partner[]>([])
const loading = ref(false)

onMounted(async () => {
  loading.value = true
  try {
    const res = await getPartnerList()
    partners.value = partners.value = [...res].sort((a, b) => b.id - a.id)
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

    <PartnersTable v-else :partners="partners" />
  </div>
</template>
