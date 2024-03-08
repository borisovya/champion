<script setup lang="ts">
import { ref } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import { FilterMatchMode } from 'primevue/api'
import { useRouter } from 'vue-router'
import type { News } from '@/types/News'
import { deleteNews } from '@/http/news/NewsServices'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import {asset} from "@/helpers/StaticHelper";

interface Props {
  news: News[] | []
}

const toast = useToast()
const route = useRouter()
const confirm = useConfirm()

const props = defineProps<Props>()

const filters = ref()
const news = ref(props.news)
const loading = ref(false)
const initFilters = () => {
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
  }
}

const requireConfirmation = (id: number) => {
  confirm.require({
    group: 'headless',
    header: `Вы действительно хотите удалить новость? Пожалуйста, подтвердите действие.`,
    message: 'Внимание!',
    accept: () => {
      deleteHandler(id)
    }
  })
}

const deleteHandler = async (id: number) => {
  loading.value = true
  try {
    const res = await deleteNews(id)
    if (res === 204) {
      news.value = news.value.filter((news) => (news as News).id !== id)
      toast.add({
        severity: 'success',
        summary: 'Готово',
        detail: 'Новость ушспешно удалена.',
        life: 3000
      })
    } else {
      toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
    }
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
  } finally {
    loading.value = false
  }
}

initFilters()
</script>

<template>
  <div class="flex flex-column">
    <Toast />
    <ConfirmDialog group="headless">
      <template #container="{ message, acceptCallback, rejectCallback }">
        <div class="flex flex-column align-items-center p-5 surface-overlay border-round">
          <div
            class="border-circle bg-primary inline-flex justify-content-center align-items-center h-6rem w-6rem -mt-8"
          >
            <i class="pi pi-question text-5xl"></i>
          </div>
          <span class="font-bold text-2xl block mb-2 mt-4">{{ (message as any).message }}</span>
          <p class="mb-0">{{ (message as any).header }}</p>
          <div class="flex align-items-center gap-2 mt-4">
            <Button label="Отменить" outlined @click="rejectCallback"></Button>
            <Button label="Удалить" @click="acceptCallback"></Button>
          </div>
        </div>
      </template>
    </ConfirmDialog>
    <h3>Новости</h3>
    <div v-if="news?.length > 0" class="flex flex-column justify-content-between">
      <DataTable
        :value="news"
        tableStyle="min-width: 50rem"
        v-model:filters="filters"
        dataKey="id"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20]"
        class="full-height"
        :globalFilterFields="['title', 'description']"
      >
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
                    v-model="filters['global'].value"
                    style="min-width: 250px"
                    placeholder="Поиск новости"
                  />
                </span>
              </div>
            </div>
          </div>
        </template>
        <Column field="newsImg" header="Изображение" style="width: 12rem">
          <template #body="{ data }">
            <div class="flex align-content-center">
              <img
                :src="asset(data.image)"
                :alt="'newsImage'"
                class="h-4rem border-round overflow-hidden"
              />
            </div>
          </template>
        </Column>
        <Column field="title" header="Заголовок" style="width: 20rem"></Column>
        <Column field="description" header="Текст"></Column>
        <Column header="" style="width: 8rem">
          <template v-slot:header></template>
          <template v-slot:body="{ data }">
            <Button
              text
              rounded
              size="large"
              icon="pi pi-eye"
              severity="secondary"
              @click="route.push(`/admin/news/show/${data.id}`)"
            />
            <Button
              text
              rounded
              size="large"
              icon="pi pi-trash"
              severity="danger"
              @click="
                () => {
                  requireConfirmation(data.id)
                }
              "
            />
          </template>
        </Column>
        <template #footer> Всего новостей: {{ news ? news.length : 0 }}.</template>
      </DataTable>
    </div>

    <div v-else class="flex flex-column justify-content-between">
      <div class="flex flex-column justify-content-between">
        <div class="flex justify-content-start p-0">
          <Button
            outlined
            icon="pi pi-megaphone"
            label="Добавить новость"
            @click="route.push('/admin/news/create')"
          />
        </div>
      </div>
      <div
        class="flex justify-content-center align-items-center"
        style="height: 400px; font-size: 20px"
      >
        <p>Новости не найдены.</p>
      </div>
    </div>
  </div>
</template>
