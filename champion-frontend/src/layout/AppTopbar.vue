<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useLayout } from '@/layout/composables/layout.ts'
import { useRouter } from 'vue-router'
import Button from 'primevue/button'
import { useUserStore } from '@/store/useStore.ts'
import { deleteFromCookie, getFromCookie } from '@/helpers/CookieHelper.ts'
import { signOut } from '@/http/auth/AuthServices.ts'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'

const { onMenuToggle } = useLayout()
const userStore = useUserStore()
const toast = useToast()

const outsideClickListener = ref(null)
const topbarMenuActive = ref(false)
const router = useRouter()
const user = userStore.getUser()

onMounted(() => {
  bindOutsideClickListener()
})

onBeforeUnmount(() => {
  unbindOutsideClickListener()
})

const onTopBarMenuButton = () => {
  topbarMenuActive.value = !topbarMenuActive.value
}
const onProfileClick = () => {
  topbarMenuActive.value = false
  router.push('/')
}

const topbarMenuClasses = computed(() => {
  return {
    'layout-topbar-menu-mobile-active': topbarMenuActive.value
  }
})

const bindOutsideClickListener = () => {
  if (!outsideClickListener.value) {
    outsideClickListener.value = (event) => {
      if (isOutsideClicked(event)) {
        topbarMenuActive.value = false
      }
    }
    document.addEventListener('click', outsideClickListener.value)
  }
}
const unbindOutsideClickListener = () => {
  if (outsideClickListener.value) {
    document.removeEventListener('click', outsideClickListener)
    outsideClickListener.value = null
  }
}
const isOutsideClicked = (event) => {
  if (!topbarMenuActive.value) return

  const sidebarEl = document.querySelector('.layout-topbar-menu')
  const topbarEl = document.querySelector('.layout-topbar-menu-button')

  return !(
    sidebarEl.isSameNode(event.target) ||
    sidebarEl.contains(event.target) ||
    topbarEl.isSameNode(event.target) ||
    topbarEl.contains(event.target)
  )
}

const onExitClickHandler = async () => {
  try {
    const res = await signOut({ token: getFromCookie('token') })
    if (res) {
      userStore.removeUser()
      deleteFromCookie('token')
      router.push('/')
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
  <div class="layout-topbar">
    <Toast position="top-right" />
    <router-link to="/" class="layout-topbar-logo">
      <img src="/layout/images/ChampionPartners_full_logo.svg" alt="logo" style="height: 80px" />
    </router-link>

    <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()">
      <i class="pi pi-bars"></i>
    </button>

    <button
      class="p-link layout-topbar-menu-button layout-topbar-button"
      @click="onTopBarMenuButton()"
    >
      <i class="pi pi-ellipsis-v"></i>
    </button>

    <div class="layout-topbar-menu" :class="topbarMenuClasses">
      <div class="flex align-items-center ml-3 mb-3 lg:ml-0 lg:mb-0">
        <i class="pi pi-user mr-2"></i>
        <span>{{ user.username }}</span>
      </div>

      <Button @click="onProfileClick" class="p-link layout-topbar-button">
        <i class="pi pi-home"></i>
        <span>Вернуться в магазин</span>
      </Button>

      <Button @click="onExitClickHandler" class="p-link layout-topbar-button">
        <i class="pi pi-sign-out"></i>
        <span>Выйти</span>
      </Button>
    </div>
  </div>
</template>

<style lang="scss" scoped></style>
