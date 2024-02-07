<script setup lang="ts">
import {ref} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import ProgressBar from 'primevue/progressbar';
import type {Partner} from '@/types/Partner';
import Dropdown from 'primevue/dropdown';


import Paginator from 'primevue/paginator';


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
  <div class="card flex flex-column" >
    <h3>Партнеры</h3>

    <div v-if="!isLoading" class="flex flex-column justify-content-between">
      <DataTable :value="partnersData" dataKey="id" :rows="filters.rows" class="full-height">
        <template #header>
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
        </template>
        <template v-if="partnersData.length === 0"><h4 class="flex justify-content-center mt-2">Партнеры не
          найдены.</h4>
        </template>

        <Column field="championId" header="Champion Id" style="min-width: 12rem; height: 60px"/>
        <Column field="email" header="Email" style="min-width: 12rem"/>
        <Column field="telegram" header="Телеграм" style="min-width: 14rem"/>
        <Column field="bonusBalance" header="Бонусный баланс" style="min-width: 12rem"/>
        <Column header="" style="min-width: 3rem">
          <template v-slot:header></template>
          <template v-slot:body="">
            <i class="pi pi-eye" style="font-size: 1.5rem"></i>
          </template>
        </Column>
      </DataTable>

      <Paginator :rows="filters.rows" :totalRecords="partnersData.length" :rowsPerPageOptions="[10, 20, 30]"/>
    </div>

    <ProgressBar v-else mode="indeterminate" style="height: 6px"></ProgressBar>
  </div>
</template>