<script setup>
import {useLayout} from '@/layout/composables/layout';
import {ref} from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import Password from 'primevue/password';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const { layoutConfig } = useLayout();
const email = ref('');
const password = ref('');
const checked = ref(false);

</script>

<template>
  <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <div class="flex flex-column align-items-center justify-content-center">
      <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, #A815EDFF 10%, rgba(33, 150, 243, 0) 30%); ">
        <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
          <div class="text-center mb-5">
            <img src="/layout/images/ChampionPartners_full_logo.svg" alt="logo" style="height: 80px;"  />
          </div>

          <div>
            <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
            <InputText id="email1" type="text" placeholder="Email" class="w-full md:w-30rem mb-5" style="padding: 1rem" v-model="email" />

            <label for="password1" class="block text-900 font-medium text-xl mb-2">Пароль</label>
            <Password id="password1" v-model="password" placeholder="Пароль" :toggleMask="true" class="w-full mb-3" inputClass="w-full" :inputStyle="{ padding: '1rem' }"></Password>

            <div class="flex align-items-center justify-content-between mb-5 gap-5">
              <div class="flex align-items-center">
<!--                <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>-->
<!--                <label for="rememberme1">Remember me</label>-->
              </div>
              <a class="font-medium no-underline ml-2 text-right cursor-pointer" style="color: #A815EDFF ">Забыли пароль?</a>
            </div>
            <Button label="Войти" class="w-full p-3 text-xl border-round-3xl" style="background: linear-gradient(270deg,#ef008f 0.26%,#a815ed 99.72%); border: none;"></Button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <AppConfig simple />
</template>

<style scoped>
.pi-eye {
  transform: scale(1.6);
  margin-right: 1rem;
}

.pi-eye-slash {
  transform: scale(1.6);
  margin-right: 1rem;
}
</style>
