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
import { isString } from 'lodash'
import { setCookie } from '@/helpers/CookieHelper.ts'
import router from '@/router/index.ts'
import { useUserStore } from '@/store/useStore.ts'

const toast = useToast()
const loading = ref(false)
const userStore = useUserStore()

const rules = computed(() => {
  return {
    username: { required, email },
    password: { required, minLength: minLength(6) }
  }
})
const loginData = reactive({
  username: '',
  password: ''
})

const v$ = useVuelidate(rules, toRefs(loginData))

const submit = async () => {
  loading.value = true
  try {
    const isValid = await v$.value.$validate()

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
      const loginResponse = await signIn(loginData)

      if (loginResponse && isString(loginResponse)) {
        userStore.setUser(JSON.parse(atob(loginResponse.split('.')[1])))

        setCookie('token', loginResponse)
        toast.add({
          severity: 'success',
          summary: 'Вы успешно зарегистрированы.',
          life: 2000
        })

        await router.push('/')
      } else {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: loginResponse?.message ? loginResponse.message : 'Попробуйте еще раз.',
          life: 2000
        })
      }
    }
  } catch (e) {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Попробуйте еще раз.',
      life: 2000
    })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div
    class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden"
  >
    <Toast position="top-right" />
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
              :disabled="loading"
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
              :disabled="loading"
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
              :loading="loading"
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
