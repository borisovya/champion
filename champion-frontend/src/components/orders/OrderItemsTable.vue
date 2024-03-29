<script setup lang="ts">
import {ref, watchEffect} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';
import {useConfirm} from 'primevue/useconfirm';
import {FilterMatchMode} from 'primevue/api';
import ConfirmDialog from 'primevue/confirmdialog';
import Tag from 'primevue/tag';
import type {Order} from '@/types/Order';

interface Props {
  orders: Order[] | []
}

const props = defineProps<Props>()

const toast = useToast()
const confirm = useConfirm()

const loading = ref(false)
const filters = ref()
const orders = ref(props.orders)
const expandedRows = ref([]);

watchEffect(() => {
  orders.value = props.orders
})

const initFilters = () => {
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
  }
}
const requireConfirmation = (id: number) => {
  confirm.require({
    group: 'headless',
    header: `Вы действительно хотите закрыть заказ? Пожалуйста, подтвердите действие.`,
    message: 'Внимание!',
    accept: () => {
      closeOrderHandler(id)
    }
  })
}

const closeOrderHandler = async (id: number) => {
  // loading.value = true
  // try {
    // const res = await deleteProduct(id)
    // if (res === 204) {
      orders.value = orders.value.map(order => {
        if(order.id === id) {
          return {
            ...order,
            status: false
          }
        } else {
          return order
        }
      })

      toast.add({
        severity: 'success',
        summary: 'Готово',
        detail: 'Заказ успешно закрыт.',
        life: 3000
      })
  //   } else {
  //     toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
  //   }
  // } catch {
  //   toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
  // } finally {
  //   loading.value = false
  // }

}

initFilters()
</script>

<template>
  <div class="flex flex-column">
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
              <Button label="Закрыть" @click="acceptCallback"></Button>
            </div>
          </div>
        </template>
      </ConfirmDialog>
      <h3>Заказы</h3>
      <div v-if="orders?.length > 0" class="flex flex-column justify-content-between">
        <DataTable
          :value="orders"
          tableStyle="min-width: 50rem"
          v-model:filters="filters"
          dataKey="id"
          paginator
          :rows="5"
          :rowsPerPageOptions="[5, 10, 20]"
          class="full-height"
          :globalFilterFields="['orderNumber', 'id', 'totalPrice']"
          v-model:expandedRows="expandedRows"
        >
          <template #header>
            <div class="flex grid justify-content-between justify-content-center pt-2">
              <div class="flex justify-content-start col-3 p-0">

              </div>
              <div class="col-9 p-0">
                <div class="flex justify-content-end">
                  <span class="p-input-icon-left ml-4">
                    <i class="pi pi-search" />
                    <InputText
                      v-model="filters['global'].value"
                      style="min-width: 250px"
                      placeholder="Поиск заказа"
                    />
                  </span>
                </div>
              </div>
            </div>
          </template>
          <Column expander style="width: 5rem" />
          <Column field="orderNumber" header="Номер заказа" style="width: 12rem" />
          <Column field="totalPrice" header="Стоимость"/>
          <Column field="createdAt" header="Дата заказа"/>
          <Column field="username" header="Email партнера"/>
          <Column field="championPartnersLogin" header="Сhampion Partners Login"/>
          <Column field="status" header="Статус заказа">
            <template #body="slotProps">
              <Tag v-if="slotProps.data.status"  severity="info" value="Активен"></Tag>
              <Tag v-else severity="success" value="Завершен"></Tag>
            </template>
          </Column>
          <Column header="" style="width: 5rem">
            <template v-slot:header></template>
            <template v-slot:body="{ data }" >
              <Button
                v-if="data.status"
                title="Закрыть заказ"
                text
                rounded
                size="large"
                icon="pi pi-check"
                severity="success"
                @click="() => requireConfirmation(data.id)"
                :disabled="loading"
              >
              </Button>
            </template>
          </Column>
          <template #expansion="slotProps">
            <div class="p-3 w-6">
              <h5>Товары в заказе {{ slotProps.data.orderNumber }}</h5>
              <DataTable :value="slotProps.data.items">
                <Column field="name" header="Название товара" />
                <Column field="price" header="Цена товара" />
                <Column field="qty" header="Количество шт" />
              </DataTable>
            </div>
          </template>
          <template #footer> Всего заказов: {{ orders ? orders.length : 0 }}.</template>
        </DataTable>
      </div>

      <div v-else class="flex flex-column justify-content-between">
        <div
          class="flex justify-content-center align-items-center"
          style="height: 400px; font-size: 20px"
        >
          <p>Заказы не найдены.</p>
        </div>
      </div>
    </div>
  </div>
</template>
