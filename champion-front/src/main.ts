import {createApp} from 'vue';
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import '@/assets/styles.scss';
import ConfirmationService from 'primevue/confirmationservice';
import App from './App.vue'
import router from './router'
import {i18n} from '@/i18n/i18n-messages';
// import { createI18n } from 'vue-i18n';

// import { Field, Form, ErrorMessage, defineRule, configure } from 'vee-validate';
// import { localize } from '@vee-validate/i18n';
// import { required, email } from '@vee-validate/rules';
//
// defineRule('required', required);
// defineRule('email', email);
//
// configure({
//   generateMessage: localize({ yup: {}}),
// });
//
// const i18n = createI18n({
//   locale: 'ru',
// });

const app = createApp(App)

app.use(i18n);
app.use(createPinia())
app.use(router)
app.use(PrimeVue, { ripple: true });
app.use(ToastService);
app.use(ConfirmationService);

// app.component('ValidationForm', Form);
// app.component('ValidationField', Field);
// app.component('ValidationErrorMessage', ErrorMessage);

app.mount('#app')
