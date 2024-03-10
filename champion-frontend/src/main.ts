import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config'
import ToastService from 'primevue/toastservice'
import '@/assets/styles.scss'
import ConfirmationService from 'primevue/confirmationservice'
import App from './App.vue'
import router from './router'
import { i18n } from '@/i18n/i18n-messages'

const app = createApp(App)

app.use(i18n)
app.use(createPinia())
app.use(router)
app.use(PrimeVue, { ripple: true })
app.use(ToastService)
app.use(ConfirmationService)

app.mount('#app')
