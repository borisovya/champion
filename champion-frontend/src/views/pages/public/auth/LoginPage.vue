<script setup>
import { computed, reactive, ref, toRefs } from 'vue'
import Password from 'primevue/password'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'
import { signIn } from '@/http/auth/AuthServices.ts'
import useVuelidate from '@vuelidate/core'
import { email, minLength, required } from '@/i18n/i18n-validators.ts'

const toast = useToast()
const loading = ref(false)
const rules = computed(() => {
  return {
    username: { required, email },
    password: { required, minLength: minLength(5) }
  }
})
const loginData = reactive({
  username: '',
  password: ''
})

const v$ = useVuelidate(rules, toRefs(loginData))
const showSuccess = () => {
  toast.add({
    severity: 'success',
    summary: 'Пока не работает, но скоро логин заработает',
    life: 2000
  })
}

const showError = () => {
  toast.add({
    severity: 'error',
    summary: 'Ой! Что-то сломалось.',
    detail: 'Попробуйте еще раз.',
    life: 2000
  })
}

const submit = async () => {
  try {
    const isValid = await v$.value.$validate()
    console.log(loginData)

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
      const registrationResponse = await signIn(loginData)
      if (registrationResponse) {
        showSuccess()
      } else {
        showError()
      }
    }
  } catch (e) {
    showError()
  }
}
</script>

<template>
  <div
    class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden"
  >
    <Toast position="bottom-right" />
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
          </div>

          <form @submit.prevent.stop="submit">
            <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
            <InputText
              id="email1"
              type="text"
              placeholder="Email"
              class="w-full md:w-30rem mb-5"
              style="padding: 1rem"
              v-model="loginData.username"
            />
            <div v-if="v$.username?.$errors[0]?.$message" class="text-red-400">
              {{ v$.username?.$errors[0]?.$message }}
            </div>

            <label for="password1" class="block text-900 font-medium text-xl mb-2">Пароль</label>
            <Password
              id="password1"
              :feedback="false"
              v-model="loginData.password"
              placeholder="Пароль"
              :toggleMask="true"
              class="w-full mb-3"
              inputClass="w-full"
              :inputStyle="{ padding: '1rem' }"
            >
            </Password>
            <div v-if="v$.password?.$errors[0]?.$message" class="text-red-400">
              {{ v$.password?.$errors[0]?.$message }}
            </div>

            <div class="flex align-items-center justify-content-between mb-5 gap-5">
              <a
                href="/register"
                class="font-medium no-underline ml-2 text-left cursor-pointer"
                style="color: #a815edff"
                >Создать аккаунт</a
              >
              <a
                href="/password-reset"
                class="font-medium no-underline ml-2 text-right cursor-pointer"
                style="color: #a815edff"
                >Забыли пароль?</a
              >
            </div>
            <Button
              label="Войти"
              type="submit"
              class="w-full p-3 text-xl border-round-3xl"
              style="
                background: linear-gradient(270deg, #ef008f 0.26%, #a815ed 99.72%);
                border: none;
              "
            >
            </Button>
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
