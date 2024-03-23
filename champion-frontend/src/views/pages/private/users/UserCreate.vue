<script setup lang="ts">
import {computed, reactive, ref, toRefs} from 'vue';
import ProgressBar from 'primevue/progressbar';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import router from '@/router';
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';
import useVuelidate from '@vuelidate/core';
import {required, email, numeric, minLength, sameAs} from '@/i18n/i18n-validators';
import {signUp} from '@/http/auth/AuthServices';
import {Roles} from '@/enum/Roles';
import Dropdown from 'primevue/dropdown';
import {useUserStore} from '@/store/useStore';
import type {User} from '@/types/User';
import {isObject, isString} from 'lodash';

const toast = useToast();
const userStore = useUserStore();
const accountData = userStore.getUser();

const roleOptions = ref([
  {name: 'Главный администратор', code: [Roles.SUPER_ADMIN]},
  {name: 'Администратор', code: [Roles.ADMIN]},
  {name: 'Партнер', code: [Roles.USER]}
]);

const loading = ref(false);
const rules = computed(() => {

  return {
    username: {required, email},
    telegramLogin: {required},
    championPartnersLogin: {required},
    balance: {numeric},
    roles: {required},
    password: {required, minLength: minLength(5)},
    confirmPassword: {
      required,
      minLength: minLength(5),
      sameAs: sameAs(`${createAccountFieldsData.password}`, 'Пароль')
    }
  };

});
const createAccountFieldsData = reactive({
  username: '',
  telegramLogin: '',
  championPartnersLogin: '',
  balance: null,
  roles: [Roles.USER],
  password: '',
  confirmPassword: ''
});

const v$ = useVuelidate(rules, toRefs(createAccountFieldsData));

const onSubmit = async () => {
  loading.value = true;
  const createRequest = {
    username: createAccountFieldsData.username,
    password: createAccountFieldsData.password,
    confirmPassword: createAccountFieldsData.confirmPassword,
    telegramLogin: createAccountFieldsData.telegramLogin,
    championPartnersLogin: createAccountFieldsData.championPartnersLogin,
    roles: createAccountFieldsData.roles
  };

  try {
    const isValid = await v$.value.$validate();

    if (!isValid) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Проверьте введенные данные.',
        life: 3000
      });
      loading.value = false;
      return;
    }
    else {
      const createPartnerRes = await signUp(createRequest);

      if (isString(createPartnerRes)) {
        toast.add({
          severity: 'success',
          summary: 'Готово',
          detail: 'Учетная запись успешно добавлена.',
          life: 2000
        });

        await router.push('/admin');
      }
      else {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: 'Попробуйте еще раз.',
          life: 3000
        });
      }
    }
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
  }
  finally {
    loading.value = false;
  }
};
</script>

<template>
  <Toast/>

  <div class="card" style="min-height: 80vh">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Добавление партнера:</h3>

      <div class="lg:flex border-round inputBlocksPaddingBottom ">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center relative" >
          <label for="email">Email</label>
          <div class="p-input-icon-left">
            <i class="pi pi-at"/>
            <InputText
                id="email"
                type="text"
                placeholder="Email"
                style="padding: 1rem; padding-left: 3rem; width: 100%"
                v-model="createAccountFieldsData.username"
            />
          </div>
          <div v-if="v$.username?.$errors[0]?.$message" class="text-red-400 absolute error">
            {{ v$.username?.$errors[0]?.$message }}
          </div>
        </div>


        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center relative">
          <label for="telegram">Телеграм</label>
          <span class="p-input-icon-left">
            <i class="pi pi-send"/>
            <InputText
                id="telegram"
                type="text"
                placeholder="Телеграм"
                style="padding: 1rem; padding-left: 3rem; width: 100%"
                v-model="createAccountFieldsData.telegramLogin"
            />
          </span>
          <div v-if="v$.telegramLogin?.$errors[0]?.$message" class="text-red-400 absolute error">
            {{ v$.telegramLogin?.$errors[0]?.$message }}
          </div>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center relative">
          <label for="password1">Пароль</label>
          <Password
              id="password1"
              v-model="createAccountFieldsData.password"
              placeholder="Пароль"
              :toggleMask="true"
              class="w-full"
              inputClass="w-full"
              :inputStyle="{ padding: '1rem' }"
          ></Password>
          <div v-if="v$.password?.$errors[0]?.$message" class="text-red-400 absolute error">
            {{ v$.password?.$errors[0]?.$message }}
          </div>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center relative">
          <label for="password2">Повтор пароля</label>
          <Password
              id="password2"
              v-model="createAccountFieldsData.confirmPassword"
              placeholder="Повтор пароля"
              :toggleMask="true"
              class="w-full"
              inputClass="w-full"
              :inputStyle="{ padding: '1rem' }"
          ></Password>
          <div v-if="v$.confirmPassword?.$errors[0]?.$message" class="text-red-400 absolute error">
            {{ v$.confirmPassword?.$errors[0]?.$message }}
          </div>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center relative">
          <label for="championLogin">Champion Partners Login</label>
          <span class="p-input-icon-left">
            <i class="pi pi-user"/>
            <InputText
                id="championLogin"
                type="text"
                placeholder="Champion Login"
                style="padding: 1rem; padding-left: 3rem; width: 100%"
                v-model="createAccountFieldsData.championPartnersLogin"
            />
          </span>
          <div v-if="v$.championPartnersLogin?.$errors[0]?.$message" class="text-red-400 absolute error">
            {{ v$.championPartnersLogin?.$errors[0]?.$message }}
          </div>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <div v-if="createAccountFieldsData.roles[0] === Roles.USER"
               class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center relative">
            <label for="balance">Бонусный баланс</label>
            <span class="p-input-icon-left">
            <i class="pi pi-wallet"/>
            <InputText
                id="balance"
                type="number"
                placeholder="Бонусный баланс"
                style="padding: 1rem; padding-left: 3rem; width: 100%"
                v-model="createAccountFieldsData.balance"
            />
          </span>
            <div v-if="v$.balance?.$errors[0]?.$message" class="text-red-400 absolute error">
              {{ v$.balance?.$errors[0]?.$message }}
            </div>
          </div>

        </div>
      </div>

      <div
          v-if="(accountData as User)?.roles[0] === Roles.SUPER_ADMIN"
          class="lg:flex border-round inputBlocksPaddingTop"
      >
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="role">Роль</label>
          <span class="p-input-icon-left">
            <i class="pi pi-user"/>
            <Dropdown
                v-model="createAccountFieldsData.roles"
                optionLabel="name"
                :options="roleOptions"
                optionValue="code"
                style="padding: 6px; width: 100%"
            />
          </span>
          <div v-if="v$.roles?.$errors[0]?.$message" class="text-red-400">
            {{ v$.roles?.$errors[0]?.$message }}
          </div>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center"></div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div class="flex mr-2">
            <Button
                label="Отменить"
                severity="danger"
                icon="pi pi-directions-alt"
                text
                @click="router.back()"
            />
          </div>
          <div class="flex">
            <Button label="Сохранить" type="submit" icon="pi pi-save" severity="success" text/>
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

.error {
  bottom: -10px;
  min-width: 260px;
}

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
