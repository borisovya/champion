<script setup lang="ts">
import Button from 'primevue/button'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/store/useStore'
import { deleteFromCookie, getFromCookie } from '@/helpers/CookieHelper'
import { signOut } from '@/http/auth/AuthServices'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'

const router = useRouter()
const userStore = useUserStore()
const user = userStore.getUser()
const toast = useToast()

const onButtonClickHandler = () => {
  router.push('/admin')
}
const loggOff = async () => {
  try {
    const res = await signOut({ token: getFromCookie('token') })
    if (res) {
      userStore.removeUser()
      deleteFromCookie('token')
      deleteFromCookie('refresh_token')
      await router.push('/login')
    } else {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Попробуйте еще раз.',
        life: 2000
      })
    }
  } catch {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Попробуйте еще раз.',
      life: 2000
    })
  }
}
</script>

<template>
  <Toast position="top-right" />
  <h1>Скоро тут будет магаз, а пока кликай по кнопке</h1>

  <div v-if="user">User data: {{ user.username ?? '-' }}</div>

  <div v-if="user" class="mt-2 mb-2">
    <Button @click="loggOff" severity="danger"> LOG OFF</Button>
  </div>

  <Button
    label="Перейти в админ панель"
    @click="onButtonClickHandler"
    class="w-auto p-3 text-xl border-round-3xl"
    style="background: linear-gradient(270deg, #ef008f 0.26%, #a815ed 99.72%); border: none"
    v-if="user.roles.every((role) => role !== 'ROLE_USER')"
  >
  </Button>
</template>
