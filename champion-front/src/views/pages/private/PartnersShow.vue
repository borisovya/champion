<script setup lang="ts">
import {onMounted, reactive, ref} from 'vue';
import type {Partner} from '@/types/Partner';
import ProgressBar from 'primevue/progressbar';
import InputText from 'primevue/inputtext';

const partner = ref<Partner | null>(null);
const loading = ref(false);

const partnerFieldsData = reactive({
  id: null,
  name: '',
  email: '',
  telegram: '',
  championId: null,
  championLogin: '',
  bonusBalance: null
});

onMounted(() => {
  loading.value = true;
  setTimeout(() => {
    partner.value = {
      id: 1,
      name: 'Ivan',
      email: 'eeeee.mail.ru',
      telegram: '@ivan',
      championId: 1,
      championLogin: '123fff',
      bonusBalance: 1000
    };

    partnerFieldsData.id = partner.value.id,
        partnerFieldsData.name = partner.value.name,
        partnerFieldsData.email = partner.value.email,
        partnerFieldsData.telegram = partner.value.telegram,
        partnerFieldsData.championId = partner.value.championId,
        partnerFieldsData.championLogin = partner.value.championLogin,
        partnerFieldsData.bonusBalance = partner.value.bonusBalance;

    loading.value = false;
  }, 1000);
});
</script>

<template>
  <div class="card" style="height: 80vh">
    <ProgressBar v-if="!partner" mode="indeterminate" style="height: 6px"></ProgressBar>

    <div v-else class="flex-column justify-content-start flex-1" style="width: 100%">
      <h3 class="ml-3">Данные авртнера:</h3>
      <div class="flex flex-wrap mt-4" style="width: 100%">
        <div class="col-12 mb-2 lg:col-4 lg:mb-0">
          <span class="p-input-icon-left">
            <i class="pi pi-at"/>
              <InputText id="email1"
                         type="text"
                         placeholder="Email"
                         class="w-full md:w-30rem"
                         style="padding: 1rem; padding-left: 3rem"
                         v-model="partnerFieldsData.email"
              />
            </span>
        </div>

        <div class="col-12 mb-2 lg:col-4 lg:mb-0">
          <span class="p-input-icon-left">
            <i class="pi pi-at"/>
              <InputText id="email1"
                         type="text"
                         placeholder="Email"
                         class="w-full md:w-30rem"
                         style="padding: 1rem; padding-left: 3rem"
                         v-model="partnerFieldsData.email"
              />
            </span>
        </div>

        <div class="col-12 mb-2 lg:col-4 lg:mb-0">
          <span class="p-input-icon-left">
            <i class="pi pi-at"/>
              <InputText id="email1"
                         type="text"
                         placeholder="Email"
                         class="w-full md:w-30rem"
                         style="padding: 1rem; padding-left: 3rem"
                         v-model="partnerFieldsData.email"
              />
            </span>
        </div>

        <div class="col-12 mb-2 lg:col-4 lg:mb-0">
          <span class="p-input-icon-left">
            <i class="pi pi-at"/>
              <InputText id="email1"
                         type="text"
                         placeholder="Email"
                         class="w-full md:w-30rem"
                         style="padding: 1rem; padding-left: 3rem"
                         v-model="partnerFieldsData.email"
              />
            </span>
        </div>
      </div>

    </div>
  </div>
</template>
