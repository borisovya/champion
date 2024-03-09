import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config'
import ToastService from 'primevue/toastservice'
import '@/assets/styles.scss'
import ConfirmationService from 'primevue/confirmationservice'
import App from './App.vue'
import router from './router'
import { i18n } from '@/i18n/i18n-messages'
import { useUserStore } from '@/store/useStore'
import { getFromCookie } from '@/helpers/CookieHelper'

const app = createApp(App)

app.use(i18n)
app.use(createPinia())
app.use(router)
app.use(PrimeVue, { ripple: true })
app.use(ToastService)
app.use(ConfirmationService)

const userStore = useUserStore()

const token = getFromCookie(import.meta.env.VITE_ACCESS_TOKEN_COOKIE)

if (token) {
  userStore.setUser(JSON.parse(atob(token.split('.')[1])))
}

app.mount('#app')
