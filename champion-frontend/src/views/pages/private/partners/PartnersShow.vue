<script setup lang="ts">
import {computed, onMounted, reactive, ref, toRefs, watchEffect} from 'vue';
import type {Partner} from '@/types/Partner';
import ProgressBar from 'primevue/progressbar';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import router from '@/router';
import Textarea from 'primevue/textarea';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import {useConfirm} from 'primevue/useconfirm';
import {useToast} from 'primevue/usetoast';
import ConfirmDialog from 'primevue/confirmdialog';
import useVuelidate from '@vuelidate/core';
import {required, email, minValue, numeric} from '@/i18n/i18n-validators';

const confirm = useConfirm();
const toast = useToast();
const partner = ref<Partner | null>(null);
const loading = ref(false);
const isSaveDisabled = ref(true);

const rules = computed(() => {
  return {
    email: {required, email},
    championId: {numeric, minValue: minValue(1)},
    telegram: {required},
    championLogin: {required},
    bonusBalance: {required, numeric, minValue: minValue(1)},
  };

});
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
  }, 100);
});
const requireConfirmation = () => {
  confirm.require({
    group: 'headless',
    header: 'Уверены, что хотите сбросить пароль?',
    message: 'Пожалуйста, подтвердите действие.',
    accept: () => {
      toast.add({severity: 'success', summary: 'Пароль успешно сброшен', life: 3000});
    },
  });
};
const getIsSaveDisabled = (): boolean => {

  if (!partnerFieldsData.email) {
    return true;
  }
  if (!partnerFieldsData.telegram) {
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

  return false;
};
const messageCancelClickHandler = () => {
  partnerFieldsData.showMessageModal = false;
  partnerFieldsData.messageForPartner = '';
};
const messageSendClickHandler = () => {
  partnerFieldsData.showMessageModal = false;
  partnerFieldsData.messageForPartner = '';
  toast.add({severity: 'success', summary: 'Confirmed', detail: 'Сообщение отправлено', life: 3000});
};

const v$ = useVuelidate(rules, toRefs(partnerFieldsData));

const onSubmit = async () => {

  const isValid = await v$.value.$validate();

  console.log(partnerFieldsData);

  if (!isValid) {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Проверьте введенные данные.', life: 3000});
    return;
  }

  toast.add({severity: 'success', summary: 'Confirmed', detail: 'Данные пользователя успешно изменены.', life: 3000});
};

watchEffect(() => {
  isSaveDisabled.value = getIsSaveDisabled();
});

</script>

<template>
  <Toast/>
  <Dialog v-model:visible="partnerFieldsData.showMessageModal" modal
          :header="'Отправка сообщения ' + partnerFieldsData.telegram" :style="{ width: '45rem' }">
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
      <Button type="button" label="Отправить" :disabled="!partnerFieldsData.messageForPartner"
              @click="messageSendClickHandler"></Button>
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

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
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
          <span v-if="v$.email?.$errors[0]?.$message" class="text-red-400">
            {{ v$.email?.$errors[0]?.$message }}
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
          <div v-if="v$.telegram?.$errors[0]?.$message" class="text-red-400">
                {{ v$.telegram?.$errors[0]?.$message }}
              </div>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="championId">Champion Id</label>
          <span class="p-input-icon-left">
            <i class="pi pi-id-card"/>
              <InputText id="championId"
                         type="number"
                         inputmode="numeric"
                         placeholder="Champion Id"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.championId"
              />
              <div v-if="v$.championId?.$errors[0]?.$message" class="text-red-400">
                {{ v$.championId?.$errors[0]?.$message }}
              </div>
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
              <div v-if="v$.championLogin?.$errors[0]?.$message" class="text-red-400">
                {{ v$.championLogin?.$errors[0]?.$message }}
              </div>
            </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center ">
          <label for="bonusBalance">Бонусный баланс</label>
          <span class="p-input-icon-left">
            <i class="pi pi-wallet"/>
              <InputText id="bonusBalance"
                         type="number"
                         placeholder="Бонусный баланс"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.bonusBalance"
              />
            <div v-if="v$.bonusBalance?.$errors[0]?.$message" class="text-red-400">
                {{ v$.bonusBalance?.$errors[0]?.$message }}
              </div>
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
                    type="submit"
                    :disabled="isSaveDisabled"
                    icon="pi pi-save"
                    severity="success"

                    text
            />
          </div>
        </div>
      </div>

    </form>
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
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
