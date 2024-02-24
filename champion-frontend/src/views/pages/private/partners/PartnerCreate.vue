<script setup lang="ts">
import { computed, reactive, ref, toRefs } from 'vue'
import ProgressBar from 'primevue/progressbar'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import router from '@/router'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import useVuelidate from '@vuelidate/core'
import { required, email, numeric, minLength, sameAs } from '@/i18n/i18n-validators'
import { signUp } from '@/http/auth/AuthServices'

const toast = useToast()

const loading = ref(false)
const rules = computed(() => {
  return {
    username: { required, email },
    telegramLogin: { required },
    championPartnersLogin: { required },
    bonusBalance: { required, numeric },
    password: { required, minLength: minLength(5) },
    confirmPassword: {
      required,
      minLength: minLength(5),
      sameAs: sameAs(`${partnerFieldsData.password}`, 'Пароль')
    }
  }
})
const partnerFieldsData = reactive({
  username: '',
  telegramLogin: '',
  championPartnersLogin: '',
  bonusBalance: null,
  password: '',
  confirmPassword: ''
})

const v$ = useVuelidate(rules, toRefs(partnerFieldsData))

const onSubmit = async () => {
  loading.value = true

  try {
    const isValid = await v$.value.$validate()
    console.log(v$.value)
    if (!isValid) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Проверьте введенные данные.',
        life: 3000
      })
      loading.value = false
      return
    } else {
      const createPartnerRes = await signUp({
        username: '',
        password: '',
        confirmPassword: '',
        telegramLogin: '',
        championPartnersLogin: ''
      })
      if (createPartnerRes) {
        toast.add({
          severity: 'success',
          summary: 'Confirmed',
          detail: 'Партнер успешно добавлен.',
          life: 2000
        })
        setTimeout(() => {
          loading.value = false
          router.push('/admin')
        }, 2000)
      } else {
        loading.value = false
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: 'Попробуйте еще раз.',
          life: 3000
        })
      }
    }
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
    loading.value = false
  }
}
</script>

<template>
  <Toast />

  <div class="card" style="min-height: 80vh">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Добавление партнера:</h3>

      <div class="lg:flex border-round inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="email">Email</label>
          <span class="p-input-icon-left">
            <i class="pi pi-at" />
            <InputText
              id="email"
              type="text"
              placeholder="Email"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.username"
            />
          </span>
          <div v-if="v$.username?.$errors[0]?.$message" class="text-red-400">
            {{ v$.username?.$errors[0]?.$message }}
          </div>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="telegram">Телеграм</label>
          <span class="p-input-icon-left">
            <i class="pi pi-send" />
            <InputText
              id="telegram"
              type="text"
              placeholder="Телеграм"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.telegramLogin"
            />
          </span>
          <div v-if="v$.telegramLogin?.$errors[0]?.$message" class="text-red-400">
            {{ v$.telegramLogin?.$errors[0]?.$message }}
          </div>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="password1">Пароль</label>
          <Password
            id="password1"
            v-model="partnerFieldsData.password"
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

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="password2">Повтор пароля</label>
          <Password
            id="password2"
            v-model="partnerFieldsData.confirmPassword"
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
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="championLogin">Champion Login</label>
          <span class="p-input-icon-left">
            <i class="pi pi-user" />
            <InputText
              id="championLogin"
              type="text"
              placeholder="Champion Login"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.championPartnersLogin"
            />
          </span>
          <div v-if="v$.championPartnersLogin?.$errors[0]?.$message" class="text-red-400">
            {{ v$.championPartnersLogin?.$errors[0]?.$message }}
          </div>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="bonusBalance">Бонусный баланс</label>
          <span class="p-input-icon-left">
            <i class="pi pi-wallet" />
            <InputText
              id="bonusBalance"
              type="number"
              placeholder="Бонусный баланс"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.bonusBalance"
            />
          </span>
          <div v-if="v$.bonusBalance?.$errors[0]?.$message" class="text-red-400">
            {{ v$.bonusBalance?.$errors[0]?.$message }}
          </div>
        </div>
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
            <Button label="Сохранить" type="submit" icon="pi pi-save" severity="success" text />
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

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
