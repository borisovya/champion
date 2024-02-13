<script setup lang="ts">
import { ref } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Paginator from 'primevue/paginator'
import { useRouter } from 'vue-router'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import type { News } from '@/types/News'

const toast = useToast()

const { news, isLoading } = defineProps({
  news: {
    type: Array as () => News[],
    required: true
  },
  isLoading: {
    type: Boolean,
    required: true
  }
})

const route = useRouter()
const loading = ref(false)

const filters = ref({
  searchValue: '',
  rows: 5
})

const deleteHandler = () => {
  loading.value = true
  try {
    toast.add({
      severity: 'success',
      summary: 'Confirmed',
      detail: 'Товар успешно деактивирован.',
      life: 3000
    })
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
  } finally {
    loading.value = false
  }
}

const showNewsHandler = () => {
  loading.value = true
  try {
    toast.add({
      severity: 'success',
      summary: 'Confirmed',
      detail: 'Товар успешно активирован.',
      life: 3000
    })
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="flex flex-column">
    <Toast />
    <h3>Новости</h3>
    <div v-if="!isLoading" class="flex flex-column justify-content-between">
      <DataTable :value="news" tableStyle="min-width: 50rem">
        <template #header>
          <div class="flex grid justify-content-between justify-content-center pt-2">
            <div class="flex justify-content-start col-3 p-0">
              <Button
                outlined
                icon="pi pi-megaphone"
                label="Добавить новость"
                @click="route.push('/admin/news/create')"
              />
            </div>
            <div class="col-9 p-0">
              <div class="flex justify-content-end">
                <span class="p-input-icon-left ml-4">
                  <i class="pi pi-search" />
                  <InputText
                    v-model="filters.searchValue"
                    style="min-width: 250px"
                    placeholder="Введите название новости"
                  />
                </span>
              </div>
            </div>
          </div>
        </template>
        <Column field="newsImg" header="Изображение" style="width: 12rem">
          <template #body="slotProps">
            <div class="flex align-content-center">
              <img
                :src="slotProps.data.newsImg"
                :alt="slotProps.data.newsImg"
                class="h-4rem border-round overflow-hidden"
              />
            </div>
          </template>
        </Column>
        <Column field="title" header="Заголовок" style="width: 20rem">
          <template #body="rowData">
            <div class="cell-content">{{ rowData.data.title }}</div>
          </template>
        </Column>
        <Column field="text" header="Текст">
          <template #body="rowData">
            <div class="cell-content">{{ rowData.data.text }}</div>
          </template>
        </Column>
        <Column header="" style="width: 8rem">
          <template v-slot:header></template>
          <template v-slot:body="{ data }">
            <Button
              text
              rounded
              size="large"
              icon="pi pi-times"
              severity="danger"
              @click="deleteHandler"
            >
            </Button>
            <Button
              text
              rounded
              size="large"
              icon="pi pi-eye"
              severity="secondary"
              @click="route.push(`/admin/news/show/${data.id}`)"
            ></Button>
          </template>
        </Column>
        <template #footer> Всего новостей: {{ news ? news.length : 0 }}.</template>
      </DataTable>

      <Paginator
        :rows="filters.rows"
        :totalRecords="news.length"
        :rowsPerPageOptions="[5, 10, 15]"
      />
    </div>
  </div>
</template>

<style>
.cell-content {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 1;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
