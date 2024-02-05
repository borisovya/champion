<script setup lang="ts">
import {onMounted, reactive, ref, watchEffect} from 'vue';
import type {Partner} from '@/types/Partner';
import ProgressBar from 'primevue/progressbar';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import router from '@/router';
import Textarea from 'primevue/textarea';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import ConfirmDialog from 'primevue/confirmdialog';


const confirm = useConfirm();
const toast = useToast();
const partner = ref<Partner | null>(null);
const loading = ref(false);
const isSaveDisabled = ref(true);

const partnerFieldsData = reactive({
  id: null,
  name: '',
  email: '',
  telegram: '',
  championId: null,
  championLogin: '',
  bonusBalance: null,
  messageForPartner: '',
  showMessageModal: false,
  showResetPasswordModal: false
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
const requireConfirmation = () => {
  confirm.require({
    group: 'headless',
    header: 'Уверены, что хотите сбросить пароль?',
    message: 'Пожалуйста, подтвердите действие.',
    accept: () => {
      toast.add({severity: 'success', summary: 'Confirmed', detail: 'Пароль успешно сброшен.', life: 3000});
    },
  });
}
const getIsSaveDisabled = (): boolean => {

  if (!partnerFieldsData.email) {
    return true;
  }
  if (!partnerFieldsData.telegram) {
    return true;
  }
  if (!partnerFieldsData.championId) {
    return true;
  }
  if (!partnerFieldsData.championLogin) {
    return true;
  }
  if (!partnerFieldsData.bonusBalance) {
    return true;
  }

  if (JSON.stringify(partner.value) === JSON.stringify({
    id: partnerFieldsData.id,
    name: partnerFieldsData.name,
    email: partnerFieldsData.email,
    telegram: partnerFieldsData.telegram,
    championId: partnerFieldsData.championId,
    championLogin: partnerFieldsData.championLogin,
    bonusBalance: partnerFieldsData.bonusBalance,
  })) {
    return true;
  }

  return false
}
const messageCancelClickHandler = () => {
  partnerFieldsData.showMessageModal = false
  partnerFieldsData.messageForPartner = ''
}
const messageSendClickHandler = () => {
  partnerFieldsData.showMessageModal = false
  partnerFieldsData.messageForPartner = ''
  toast.add({severity: 'success', summary: 'Confirmed', detail: 'Сообщение отправлено', life: 3000});
}

const onChampionIdChange = (event: Event) => {
  const inputElement = event.target as HTMLInputElement;
  const digitsOnly = inputElement.value.replace(/\D/g, '');
  if(digitsOnly === '') {
    partnerFieldsData.championId=''
  } else {
    partnerFieldsData.championId = Number(digitsOnly);
  }
};
const onBonusBalanceChange = (event: Event) => {
  const inputElement = event.target as HTMLInputElement;
  const digitsOnly = inputElement.value.replace(/\D/g, '');
  if(digitsOnly === '') {
    partnerFieldsData.bonusBalance=''
  } else {
    partnerFieldsData.bonusBalance = Number(digitsOnly);
  }
};

const onSaveClickHandler = () => {
  console.log(partnerFieldsData);
};

watchEffect(() => {
  isSaveDisabled.value = getIsSaveDisabled();
});

</script>

<template>
  <Toast/>
  <Dialog v-model:visible="partnerFieldsData.showMessageModal" modal :header="'Отправка сообщения ' + partnerFieldsData.telegram"  :style="{ width: '45rem' }">
    <span class="p-text-secondary block mb-3">Текст сообщения:</span>
    <div class="flex align-items-center gap-3 mb-3">
      <Textarea id="message"
                rows="7"
                v-model="partnerFieldsData.messageForPartner"
                class="flex-auto"
                autocomplete="off"
      />
    </div>
    <div class="flex justify-content-end gap-2">
      <Button type="button" label="Отменить" severity="secondary" @click="messageCancelClickHandler"></Button>
      <Button type="button" label="Отправить" :disabled="!partnerFieldsData.messageForPartner" @click="messageSendClickHandler"></Button>
    </div>
  </Dialog>

  <ConfirmDialog group="headless">
    <template #container="{ message, acceptCallback, rejectCallback }">
      <div class="flex flex-column align-items-center p-5 surface-overlay border-round">
        <div class="border-circle bg-primary inline-flex justify-content-center align-items-center h-6rem w-6rem -mt-8">
          <i class="pi pi-question text-5xl"></i>
        </div>
        <span class="font-bold text-2xl block mb-2 mt-4">{{ (message as any).message }}</span>
        <p class="mb-0">{{ (message as any).header }}</p>
        <div class="flex align-items-center gap-2 mt-4">
          <Button label="Сбросить" @click="acceptCallback"></Button>
          <Button label="Отменить" outlined @click="rejectCallback"></Button>
        </div>
      </div>
    </template>
  </ConfirmDialog>

  <div class="card" style="min-height: 80vh">
    <ProgressBar v-if="!partner" mode="indeterminate" style="height: 6px"></ProgressBar>

    <div v-else class="p-grid p-fluid p-justify-center">
      <h3 class="ml-3">Данные партнера {{ partnerFieldsData.id }}:</h3>

      <div class="lg:flex border-round inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="email">Email</label>
          <span class="p-input-icon-left">
            <i class="pi pi-at"/>
              <InputText id="email"
                         type="text"
                         placeholder="Email"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.email"
              />
            </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="telegram">Телеграм</label>
          <span class="p-input-icon-left">
            <i class="pi pi-send"/>
              <InputText id="telegram"
                         type="text"
                         placeholder="Телеграм"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.telegram"
              />
            </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="championId">Champion Id</label>
          <span class="p-input-icon-left">
            <i class="pi pi-id-card"/>
              <InputText id="championId"
                         type="text"
                         inputmode="numeric"
                         placeholder="Телеграм"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         :value="partnerFieldsData.championId"
                         @input="onChampionIdChange($event)"
              />
            </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="championLogin">Champion Login</label>
          <span class="p-input-icon-left">
            <i class="pi pi-user"/>
              <InputText id="championLogin"
                         type="text"
                         placeholder="Телеграм"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.championLogin"
              />
            </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center ">
          <label for="bonusBalance">Бонусный баланс</label>
          <span class="p-input-icon-left">
            <i class="pi pi-wallet"/>
              <InputText id="bonusBalance"
                         type="text"
                         placeholder="Бонусный баланс"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         :value="partnerFieldsData.bonusBalance"
                         @input="onBonusBalanceChange($event)"
              />
            </span>
        </div>

        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">

        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div class="flex mr-2">
            <Button label="Отправить сообщение"
                    icon="pi pi-envelope"
                    text
                    @click="partnerFieldsData.showMessageModal = true"
            />
          </div>
          <div class="flex mr-2">
            <Button label="Сбросить пароль"
                    icon="pi pi-key"
                    text
                    @click="requireConfirmation()"
            />
          </div>
          <div class="flex mr-2">
            <Button label="Отменить"
                    severity="danger"
                    icon="pi pi-directions-alt"
                    text
                    @click="router.back()"
            />
          </div>
          <div class="flex">
            <Button label="Сохранить"
                    :disabled="isSaveDisabled"
                    icon="pi pi-save"
                    severity="success"
                    @click="onSaveClickHandler"
                    text
            />
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>
.inputBlocksPaddingBottom {
  padding: 5px;
}

@media (max-width: 1023px) {
  .inputBlocksPaddingBottom {
    padding-bottom: 0;
  }
}

.inputBlocksPaddingTop {
  padding: 5px;
}

@media (max-width: 992px) {
  .inputBlocksPaddingTop {
    padding-top: 0;
  }
}
</style>
