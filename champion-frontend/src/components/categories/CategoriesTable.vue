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
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'

interface Props {
  categories: Category[]
}

const toast = useToast()
const confirm = useConfirm()

const props = defineProps<Props>()
const categories = ref(props.categories)
const idForDeleting = ref<number | null>(null)

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
        summary: 'Готово',
        detail: (res as Category).status
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

const requireConfirmation = () => {
  confirm.require({
    group: 'headless',
    header:
      'Удаление категории повлечет удаление всех товаров, входящих в нее. Пожалуйста, подтвердите действие.',
    message: 'Внимание!',
    accept: () => {
      deleteHandler(idForDeleting.value)
    }
  })
}

const deleteHandler = async (id: number) => {
  loading.value = true
  try {
    const res = await deleteCategory(id)
    if (res === 204) {
      categories.value = categories.value.filter((category) => category.id !== id)
      toast.add({
        severity: 'success',
        summary: 'Готово',
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
            <Button label="Отменить" outlined @click="rejectCallback" />
            <Button label="Удалить" @click="acceptCallback" />
          </div>
        </div>
      </template>
    </ConfirmDialog>
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
        <Column field="name" header="Название" style="max-width: 180px"></Column>
        <Column field="status" header="Статус" style="min-width: 150px">
          <template #body="slotProps">
            <Tag v-if="slotProps.data.status" severity="success" value="Активна"></Tag>
            <Tag v-else class="text-red-500" severity="danger" value="Не активна"></Tag>
          </template>
        </Column>
        <Column header="" style="width: 110px">
          <template v-slot:header></template>
          <template v-slot:body="{ data }">
            <Button
              v-if="data.status"
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
              @click="
                () => {
                  idForDeleting = data.id
                  requireConfirmation()
                }
              "
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
