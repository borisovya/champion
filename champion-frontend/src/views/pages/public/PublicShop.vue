<script setup lang="ts">
import Button from 'primevue/button'
import { useRouter } from 'vue-router'
import {useUserStore} from '@/store/useStore';
import {deleteFromCookie} from '@/helpers/CookieHelper';

const router = useRouter()
const userStore = useUserStore()
const user = userStore.getUser()

const onButtonClickHandler = () => {
  router.push('/admin')
}

const loggOff = () => {
  userStore.removeUser()
  deleteFromCookie('token')
  router.push('/login')
}
</script>

<template>
  <h1>Скоро тут будет магаз, а пока кликай по кнопке</h1>

  <div v-if="user">
    User data: {{ user.username }}
  </div>

  <div v-if="user" class="mt-2 mb-2">
    <Button @click="loggOff" severity="danger"> LOG OFF</Button>
  </div>



  <Button
    label="Перейти в админ панель"
    @click="onButtonClickHandler"
    class="w-auto p-3 text-xl border-round-3xl"
    style="background: linear-gradient(270deg, #ef008f 0.26%, #a815ed 99.72%); border: none"
  >
  </Button>
</template>
