<script setup lang="ts">
import {ref} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
const toast = useToast();
import Paginator from 'primevue/paginator';
import {useRouter} from 'vue-router';
import {Product} from '@/types/Products';
import router from '@/router';
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';

const {products, isLoading} = defineProps({
  products: {
    type: Array as () => Product[],
    required: true,
  },
  isLoading: {
    type: Boolean,
    required: true,
  }
});

const route = useRouter();
const loading = ref(false);
const dropdownValues = ref([
  {name: 'Id товара', code: 'id'},
  {name: 'Название', code: 'name'},
  {name: 'Категория', code: 'categoryId'},
]);

const filters = ref({
  searchValue: '',
  fieldForSearch: 'name',
  rows: 5
});

const deactivateHandler = () => {
  loading.value = true;
  try {
    toast.add({severity: 'success', summary: 'Confirmed', detail: 'Товар успешно деактивирован.', life: 3000});
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
  } finally {
    loading.value = false;
  }
}

const activateHandler = () => {
  loading.value = true;
  try {
    toast.add({severity: 'success', summary: 'Confirmed', detail: 'Товар успешно активирован.', life: 3000});
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
  } finally {
    loading.value = false;
  }
}

</script>

<template>
  <div class=" flex flex-column">
    <Toast/>
    <h3>Витрина</h3>
    <div v-if="!isLoading" class="flex flex-column justify-content-between">
      <DataTable :value="products" tableStyle="min-width: 50rem">
        <template #header>
          <div class="flex flex-wrap align-items-center justify-content-between gap-2">
            <!--              <span class="text-xl text-900 font-bold">Товары</span>-->
            <!--              <Button icon="pi pi-refresh" rounded raised />-->
          </div>

          <div class="flex grid justify-content-between justify-content-center pt-2">
            <div class="flex justify-content-start col-3 p-0">
              <Button outlined
                      icon="pi pi-box"
                      label="Добавить товар"
                      @click="route.push('/admin/shop/create')"
              />
            </div>
            <div class="col-9 p-0">
              <div class="flex justify-content-end">
                <Dropdown v-model="filters.fieldForSearch"
                          :options="dropdownValues"
                          optionLabel="name"
                          optionValue="code"
                          class="w-full w-12rem"
                />

                <span class="p-input-icon-left ml-4">
                        <i class="pi pi-search"/>
                        <InputText v-model="filters.searchValue"
                                   placeholder="Введите значение"
                        />
                    </span>
              </div>
            </div>
          </div>

        </template>
        <Column field="imgUrl" header="Изображение">
          <template #body="slotProps">
            <div class="flex align-content-center justify-content-center">
              <img :src="slotProps.data.imgUrl" :alt="slotProps.data.image" class=" h-5rem border-round overflow-hidden"/>
            </div>
          </template>
        </Column>
        <Column field="name" header="Название"></Column>
        <Column field="description" header="Описание"></Column>
        <Column field="price" header="Цена"></Column>
        <Column field="categoryId" header="Категория"></Column>
        <Column field="active" header="Статус">
          <template #body="slotProps">
            <Tag v-if="slotProps.data.active" severity="success" value="Активен"></Tag>
            <Tag v-else class="text-red-500" severity="danger" value="Не активен"></Tag>
          </template>
        </Column>
        <Column header="" style="min-width: 3rem">
          <template v-slot:header></template>
          <template v-slot:body="{data}">
            <Button v-if="data.active"
                    text
                    rounded
                    size="large"
                    icon="pi pi-times"
                    severity="danger"
                    @click="deactivateHandler">

            </Button>
            <Button v-else
                    text
                    rounded
                    size="large"
                    icon="pi pi-check"
                    severity="success"
                    @click="activateHandler">

            </Button>
            <Button text rounded size="large" icon="pi pi-eye" severity="secondary"
                    @click="route.push(`/admin/shop/show/${data.id}`)"></Button>
          </template>
        </Column>
        <template #footer> Всего {{ products ? products.length : 0 }} товаров.</template>
      </DataTable>


      <Paginator :rows="filters.rows" :totalRecords="products.length" :rowsPerPageOptions="[5, 10, 15]"/>
    </div>
  </div>
</template>