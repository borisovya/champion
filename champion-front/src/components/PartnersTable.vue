<script setup lang="ts">
import {ref} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import type {Partner} from '@/types/Partner';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';

import Paginator from 'primevue/paginator';
import {useRouter} from 'vue-router';

const {partnersData, isLoading} = defineProps({
  partnersData: {
    type: Array as () => Partner[],
    required: true,
  },
  isLoading: {
    type: Boolean,
    required: true,
  }
});

const route = useRouter()

const dropdownValues = ref([
  {name: 'Champion Id', code: 'championId'},
  {name: 'Email', code: 'email'},
  {name: 'Telegram', code: 'telegram'},
]);

const filters = ref({
  searchValue: '',
  fieldForSearch: 'email',
  rows: 10
});

</script>

<template>
  <div class=" flex flex-column" >
    <h3>Партнеры</h3>

    <div v-if="!isLoading" class="flex flex-column justify-content-between">
      <DataTable :value="partnersData" dataKey="id" :rows="filters.rows" class="full-height">
        <template #header>
          <div class="flex grid justify-content-between justify-content-center pt-2">
            <div class="flex justify-content-start col-3 p-0">
              <Button outlined
                      icon="pi pi-user-plus"
                      label="Добавить партнера"
                      @click="route.push('/admin/partner/create')"
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
        <template v-if="partnersData.length === 0"><h4 class="flex justify-content-center mt-2">Партнеры не
          найдены.</h4>
        </template>

        <Column field="championId" header="Champion Id" style="min-width: 12rem; height: 60px"/>
        <Column field="championLogin" header="Champion Login" style="min-width: 12rem; height: 60px"/>
        <Column field="email" header="Email" style="min-width: 12rem"/>
        <Column field="telegram" header="Телеграм" style="min-width: 14rem"/>
        <Column field="bonusBalance" header="Бонусный баланс" style="min-width: 12rem"/>
        <Column header="" style="min-width: 3rem">
          <template v-slot:header></template>
          <template v-slot:body="{data}">
            <Button text rounded size="large" icon="pi pi-eye" severity="secondary" @click="route.push(`/admin/partner/show/${data.id}`)"></Button>
          </template>
        </Column>
      </DataTable>

      <Paginator :rows="filters.rows" :totalRecords="partnersData.length" :rowsPerPageOptions="[10, 20, 30]"/>
    </div>
  </div>
</template>