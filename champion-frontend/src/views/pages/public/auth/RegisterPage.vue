<script setup>
import {computed, reactive, ref, toRefs} from 'vue';
import Password from 'primevue/password';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import {useToast} from 'primevue/usetoast';
import Toast from 'primevue/toast';
import useVuelidate from '@vuelidate/core';
import {required, email, minLength, sameAs} from '@/i18n/i18n-validators';

const toast = useToast();
const loading = ref(false);
const rules = computed(() => {
  return {
    email: {required, email},
    telegram: {required, minLength: minLength(2)},
    championLogin: {required},
    password: {required, minLength: minLength(5)},
    repeatedPassword: {required, minLength: minLength(5), sameAs: sameAs(`${registrationData.password}`, 'Пароль')}
  };
});
const registrationData = reactive({
  email: '',
  password: '',
  repeatedPassword: '',
  telegram: '',
  championLogin: '',
})

const showSuccess = () => {
  toast.add({
    severity: 'success',
    summary: 'Пока не работает, но скоро регистрация заработает',
    detail: `${JSON.stringify(registrationData)}`,
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

const submit = async () => {
  try {
    const isValid = await v$.value.$validate();
    console.log(registrationData);

    if (!isValid) {
      toast.add({severity: 'error', summary: 'Ошибка', detail: 'Проверьте введенные данные.', life: 3000});
      loading.value = false;
      return;
    }
    else showSuccess();
  } catch (e) {
    showError();
  }
};

const v$ = useVuelidate(rules, toRefs(registrationData));

</script>

<template>
  <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <Toast position="bottom-right"/>
    <div class="flex flex-column align-items-center justify-content-center">
      <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, #A815EDFF 10%, rgba(33, 150, 243, 0) 30%); ">
        <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
          <div class="text-center mb-5">
            <img src="/layout/images/ChampionPartners_full_logo.svg" alt="logo" style="height: 80px;"  />
            <p class="text-4xl font-bold">Регистрация</p>
          </div>

          <form @submit.prevent.stop="submit">
            <div class="mb-2">
              <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
              <InputText id="email1" type="text" placeholder="Email" class="w-full md:w-30rem" style="padding: 1rem" v-model="registrationData.email" />
              <div v-if="v$.email?.$errors[0]?.$message" class="text-red-400">
                {{ v$.email?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-2">
              <label for="telegram1" class="block text-900 text-xl font-medium mb-2">Телеграм</label>
              <InputText id="telegram1" type="text" placeholder="Телеграм" class="w-full md:w-30rem" style="padding: 1rem" v-model="registrationData.telegram" />
              <div v-if="v$.telegram?.$errors[0]?.$message" class="text-red-400">
                {{ v$.telegram?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-2">
              <label for="champId" class="block text-900 text-xl font-medium mb-2">Champion Login</label>
              <InputText id="champId" type="text" placeholder="Champion Login" class="w-full md:w-30rem" style="padding: 1rem" v-model="registrationData.championLogin" />
              <div v-if="v$.championLogin?.$errors[0]?.$message" class="text-red-400">
                {{ v$.championLogin?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-2">
              <label for="password1" class="block text-900 font-medium text-xl mb-2">Пароль</label>
              <Password id="password1" v-model="registrationData.password" placeholder="Пароль" :toggleMask="true" class="w-full" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>
              <div v-if="v$.password?.$errors[0]?.$message" class="text-red-400">
                {{ v$.password?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-4">
              <label for="password2" class="block text-900 font-medium text-xl mb-2">Повтор пароля</label>
              <Password id="password2" v-model="registrationData.repeatedPassword" placeholder="Повтор пароля" :toggleMask="true" class="w-full" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>
              <div v-if="v$.repeatedPassword?.$errors[0]?.$message" class="text-red-400">
                {{ v$.repeatedPassword?.$errors[0]?.$message }}
              </div>
            </div>
            <Button type="submit" label="Зарегистрироваться" class="w-full p-3 text-xl border-round-3xl" style="background: linear-gradient(270deg,#ef008f 0.26%,#a815ed 99.72%); border: none;"></Button>
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

<style scoped>

</style>
