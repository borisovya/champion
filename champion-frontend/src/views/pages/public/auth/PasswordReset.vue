<script setup>
import {ref} from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';

const toast = useToast();
const email = ref('')

const showSuccess = () => {
  toast.add({
    severity: 'success',
    summary: 'Инструкции отправлены на указанный email.',
    life: 2000,
  });
};

const showError = () => {
  toast.add({
    severity: 'error',
    summary: 'Ой! Что-то сломалось. Попробуете еще раз?',
    life: 2000,
  });
};

const submit = () => {
  try {
    showSuccess();
    email.value = ''

  } catch (e) {
    if(e) {
      showError();
    }
  }
};

</script>

<template>
  <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <Toast position="bottom-right"/>
    <div class="flex flex-column align-items-center justify-content-center">
      <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, #A815EDFF 10%, rgba(33, 150, 243, 0) 30%); ">
        <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
          <div class="text-center mb-5">
            <img src="/layout/images/ChampionPartners_full_logo.svg" alt="logo" style="height: 80px;"/>
            <p class="text-4xl font-bold">Восстановление пароля</p>
          </div>

          <form @submit.prevent.stop="submit">
            <label for="email1" class="block text-900 text-xl font-medium mb-2">Введите Email</label>
            <InputText id="email1"
                       type="text"
                       placeholder="Email"
                       class="w-full md:w-30rem mb-5"
                       style="padding: 1rem"
                       v-model="email"/>

            <div class="flex align-items-center justify-content-between mb-5 gap-5">
              <Button label="Восстановить"
                      type="submit"
                      class="w-full p-3 text-xl border-round-3xl"
                      style="background: linear-gradient(270deg,#ef008f 0.26%,#a815ed 99.72%); border: none;"></Button>
            </div>
          </form>

          <div class="text-center mt-5">
            <a href="/admin"
               class="font-lg no-underline cursor-pointer text-xl"
               style="color: #A815EDFF ">
              На главную
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <AppConfig simple/>
</template>

<style scoped>

</style>
