<script setup>
import {reactive} from 'vue';
import Password from 'primevue/password';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import {useToast} from 'primevue/usetoast';
import Toast from 'primevue/toast';

const toast = useToast();

const loginData = reactive({
  email: '',
  password: '',
});

const showSuccess = () => {
  toast.add({
    severity: 'success',
    summary: 'Пока не работает, но скоро логин заработает',
    life: 2000,
  });
};

const showError = () => {
  toast.add({
    severity: 'error',
    summary: 'Ой! Что-то сломалось.',
    detail: 'Но это не точно...',
    life: 2000,
  });
};

const submit = () => {
  try {
    showSuccess();
    loginData.email = '';
    loginData.password = '';
  } catch (e) {
    showError();
  }
};

</script>

<template>
  <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <Toast position="bottom-right"/>
    <div class="flex flex-column align-items-center justify-content-center">
      <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, #A815EDFF 10%, rgba(33, 150, 243, 0) 30%)">
        <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
          <div class="text-center mb-5">
            <img src="/layout/images/ChampionPartners_full_logo.svg" alt="logo" style="height: 80px;"/>
          </div>

          <form @submit.prevent.stop="submit">
            <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
            <InputText id="email1" type="text" placeholder="Email" class="w-full md:w-30rem mb-5" style="padding: 1rem"
                       v-model="loginData.email"/>

            <label for="password1" class="block text-900 font-medium text-xl mb-2">Пароль</label>
            <Password id="password1"
                      :feedback="false"
                      v-model="loginData.password"
                      placeholder="Пароль"
                      :toggleMask="true"
                      class="w-full mb-3"
                      inputClass="w-full"
                      :inputStyle="{ padding: '1rem' }">
            </Password>

            <div class="flex align-items-center justify-content-between mb-5 gap-5">
              <a href="/register" class="font-medium no-underline ml-2 text-left cursor-pointer"
                 style="color: #A815EDFF ">Создать аккаунт</a>
              <a href="/password-reset" class="font-medium no-underline ml-2 text-right cursor-pointer"
                 style="color: #A815EDFF ">Забыли пароль?</a>
            </div>
            <Button label="Войти"
                    type="submit"
                    class="w-full p-3 text-xl border-round-3xl"
                    style="background: linear-gradient(270deg,#ef008f 0.26%,#a815ed 99.72%); border: none;">
            </Button>
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
</template>

<style scoped></style>
