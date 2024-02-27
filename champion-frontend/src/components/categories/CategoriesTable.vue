<script setup lang="ts">
import { ref } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import { useRouter } from 'vue-router'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import type { Category } from '@/types/Category'
import { deleteCategory, toggleCategoryStatus } from '@/http/categories/CategoriesServices'

interface Props {
  categories: Category[]
}

const toast = useToast()

const props = defineProps<Props>()
const categories = ref(props.categories)

const route = useRouter()
const loading = ref(false)

const toggleHandler = async (id: number) => {
  loading.value = true
  try {
    const res = await toggleCategoryStatus(id)

    if ((res as Category).id) {
      categories.value = categories.value.map((category) =>
        category.id === id ? { ...category, status: (res as Category).status } : category
      )
      toast.add({
        severity: 'success',
        summary: 'Confirmed',
        detail:
          (res as Category).status === 0
            ? 'Категория успешно деактивирована.'
            : 'Категория успешно активирована.',
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

const deleteHandler = async (id: number) => {
  loading.value = true
  try {
    const res = await deleteCategory(id)
    if (res === 204) {
      categories.value = categories.value.filter((category) => category.id !== id)
      toast.add({
        severity: 'success',
        summary: 'Confirmed',
        detail: 'Категория успешно удалена.',
        life: 3000
      })
    }
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
    <h3>Категории товаров</h3>
    <div v-if="categories.length > 0" class="flex flex-column justify-content-between">
      <DataTable
        :value="categories"
        tableStyle="min-width: 50rem"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20]"
      >
        <template #header>
          <div class="flex grid justify-content-between justify-content-center pt-2">
            <div class="flex justify-content-start p-0">
              <Button
                outlined
                icon="pi pi-plus"
                label="Добавить категорию"
                @click="route.push('/admin/shop/category/create')"
              />
            </div>
          </div>
        </template>
        <Column field="name" header="Название"></Column>
        <Column field="status" header="Статус" style="min-width: 100px">
          <template #body="slotProps">
            <Tag v-if="slotProps.data.status === 1" severity="success" value="Активна"></Tag>
            <Tag v-else class="text-red-500" severity="danger" value="Не активна"></Tag>
          </template>
        </Column>
        <Column header="" style="width: 110px">
          <template v-slot:header></template>
          <template v-slot:body="{ data }">
            <Button
              v-if="data.status === 1"
              title="Деактивировать"
              text
              rounded
              size="large"
              icon="pi pi-times"
              severity="danger"
              @click="() => toggleHandler(data.id)"
              :disabled="loading"
            >
            </Button>
            <Button
              v-else
              text
              rounded
              title="Активировать"
              size="large"
              icon="pi pi-check"
              severity="success"
              @click="() => toggleHandler(data.id)"
              :disabled="loading"
            >
            </Button>
            <Button
              text
              rounded
              size="large"
              icon="pi pi-trash"
              severity="danger"
              title="Удалить"
              :disabled="loading"
              @click="() => deleteHandler(data.id)"
            >
            </Button>
          </template>
        </Column>
      </DataTable>
    </div>
    <div v-else class="flex flex-column justify-content-between">
      <div class="flex flex-column justify-content-between">
        <div class="flex justify-content-start p-0">
          <Button
            outlined
            icon="pi pi-plus"
            label="Добавить категорию"
            @click="route.push('/admin/shop/category/create')"
          />
        </div>
      </div>
      <div
        class="flex justify-content-center align-items-center"
        style="height: 400px; font-size: 20px"
      >
        <p>Нет доступных категорий.</p>
      </div>
    </div>
  </div>
</template>
