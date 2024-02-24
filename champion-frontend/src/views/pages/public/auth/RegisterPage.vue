<script setup>
import {computed, reactive, ref, toRefs} from 'vue';
import Password from 'primevue/password';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import {useToast} from 'primevue/usetoast';
import Toast from 'primevue/toast';
import useVuelidate from '@vuelidate/core';
import {required, email, minLength, sameAs} from '@/i18n/i18n-validators';
import {signUp} from '@/http/auth/AuthServices.ts';
import {setCookie} from '@/helpers/CookieHelper.ts';
import {isString} from 'lodash';
import router from '@/router/index.ts';
import {useUserStore} from '@/store/useStore.ts';

const toast = useToast();
const userStore = useUserStore()

const loading = ref(false);
const rules = computed(() => {
  return {
    username: {required, email},
    telegramLogin: {required, minLength: minLength(2)},
    championPartnersLogin: {required},
    password: {required, minLength: minLength(6)},
    confirmPassword: {
      required,
      minLength: minLength(5),
      sameAs: sameAs(`${registrationData.password}`, 'Пароль'),
    },
  };
});
const registrationData = reactive({
  username: '',
  password: '',
  confirmPassword: '',
  telegramLogin: '',
  championPartnersLogin: '',
});

const v$ = useVuelidate(rules, toRefs(registrationData));

const submit = async () => {
  loading.value = true;
  try {
    const isValid = await v$.value.$validate();

    if (!isValid) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Проверьте введенные данные.',
        life: 3000,
      });
      loading.value = false;
      return;
    } else {
      const registrationResponse = await signUp(registrationData);

      if (registrationResponse && isString(registrationResponse)) {
        userStore.setUser(JSON.parse(atob(registrationResponse.split('.')[1])))

        setCookie('token', registrationResponse);
        toast.add({
          severity: 'success',
          summary: 'Вы успешно зарегистрированы.',
          life: 2000,
        });
        setTimeout(() => {
          loading.value = false;
          router.push('/');
        }, 2000);

      } else {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: registrationResponse?.message ? registrationResponse.message : 'Попробуйте еще раз.',
          life: 2000,
        });
      }
    }
  } catch (e) {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Попробуйте еще раз.',
      life: 2000,
    });
  }
};
</script>

<template>
  <div
      class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden"
  >
    <Toast position="top-right"/>
    <div class="flex flex-column align-items-center justify-content-center">
      <div
          style="
          border-radius: 56px;
          padding: 0.3rem;
          background: linear-gradient(180deg, #a815edff 10%, rgba(33, 150, 243, 0) 30%);
        "
      >
        <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
          <div class="text-center mb-5">
            <img
                src="/layout/images/ChampionPartners_full_logo.svg"
                alt="logo"
                style="height: 80px"
            />
            <p class="text-4xl font-bold">Регистрация</p>
          </div>

          <form @submit.prevent.stop="submit">
            <div class="mb-2">
              <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
              <InputText
                  :disabled="loading"
                  id="email1"
                  type="text"
                  placeholder="Email"
                  class="w-full md:w-30rem"
                  style="padding: 1rem"
                  v-model="registrationData.username"
              />
              <div v-if="v$.username?.$errors[0]?.$message" class="text-red-400">
                {{ v$.username?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-2">
              <label for="telegram1" class="block text-900 text-xl font-medium mb-2"
              >Телеграм</label
              >
              <InputText
                  :disabled="loading"
                  id="telegram1"
                  type="text"
                  placeholder="Телеграм"
                  class="w-full md:w-30rem"
                  style="padding: 1rem"
                  v-model="registrationData.telegramLogin"
              />
              <div v-if="v$.telegramLogin?.$errors[0]?.$message" class="text-red-400">
                {{ v$.telegramLogin?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-2">
              <label for="champId" class="block text-900 text-xl font-medium mb-2"
              >Champion Partners Login</label
              >
              <InputText
                  :disabled="loading"
                  id="champId"
                  type="text"
                  placeholder="Champion Partners Login"
                  class="w-full md:w-30rem"
                  style="padding: 1rem"
                  v-model="registrationData.championPartnersLogin"
              />
              <div v-if="v$.championPartnersLogin?.$errors[0]?.$message" class="text-red-400">
                {{ v$.championPartnersLogin?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-2">
              <label for="password1" class="block text-900 font-medium text-xl mb-2">Пароль</label>
              <Password
                  :disabled="loading"
                  id="password1"
                  v-model="registrationData.password"
                  placeholder="Пароль"
                  :toggleMask="true"
                  class="w-full"
                  inputClass="w-full"
                  :inputStyle="{ padding: '1rem' }"
              ></Password>
              <div v-if="v$.password?.$errors[0]?.$message" class="text-red-400">
                {{ v$.password?.$errors[0]?.$message }}
              </div>
            </div>
            <div class="mb-4">
              <label for="password2" class="block text-900 font-medium text-xl mb-2"
              >Повтор пароля</label
              >
              <Password
                  :disabled="loading"
                  id="password2"
                  v-model="registrationData.confirmPassword"
                  placeholder="Повтор пароля"
                  :toggleMask="true"
                  class="w-full"
                  inputClass="w-full"
                  :inputStyle="{ padding: '1rem' }"
              ></Password>
              <div v-if="v$.confirmPassword?.$errors[0]?.$message" class="text-red-400">
                {{ v$.confirmPassword?.$errors[0]?.$message }}
              </div>
            </div>
            <Button
                :loading="loading"
                type="submit"
                label="Зарегистрироваться"
                class="w-full p-3 text-xl border-round-3xl"
                style="
                background: linear-gradient(270deg, #ef008f 0.26%, #a815ed 99.72%);
                border: none;
              "
            ></Button>
          </form>

          <div class="text-center mt-5">
            <a
                href="/admin"
                class="font-lg no-underline cursor-pointer text-xl"
                style="color: #a815edff"
            >
              На главную
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
