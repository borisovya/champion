<script setup lang="ts">
import {computed, reactive, ref, toRefs} from 'vue';
import ProgressBar from 'primevue/progressbar';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import router from '@/router';
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';
import useVuelidate from '@vuelidate/core';
import {required, email, minValue, numeric} from '@/i18n/i18n-validators';

const toast = useToast();

const loading = ref(false);
const rules = computed(() => {
  return {
    email: {required, email},
    championId: { numeric, minValue: minValue(1)},
    telegram: {required},
    championLogin: {required},
    bonusBalance: {required, numeric, minValue: minValue(1)},
  };
});
const partnerFieldsData = reactive({
  name: '',
  email: '',
  telegram: '',
  championId: null,
  championLogin: '',
  bonusBalance: null,
});

const v$ = useVuelidate(rules, toRefs(partnerFieldsData));

const onSubmit = async () => {
  loading.value = true;

  try {
    const isValid = await v$.value.$validate();
    console.log(v$.value);
    if (!isValid) {
      toast.add({severity: 'error', summary: 'Ошибка', detail: 'Проверьте введенные данные.', life: 3000});
      loading.value = false;
      return;
    }
    toast.add({severity: 'success', summary: 'Confirmed', detail: 'Партнер успешно добавлен.', life: 2000});
    setTimeout(() => {
      loading.value = false;
      router.push('/admin');
    }, 2000);
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
    loading.value = false;
  }
};

</script>

<template>
  <Toast/>

  <div class="card" style="min-height: 80vh">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Добавление партнера:</h3>

      <div class="lg:flex border-round inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="email">Email</label>
          <span class="p-input-icon-left">
            <i class="pi pi-at"/>
              <InputText id="email"
                         type="text"
                         placeholder="Email"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.email"
              />
            </span>
          <span v-if="v$.email?.$errors[0]?.$message" class="text-red-400">
            {{ v$.email?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="telegram">Телеграм</label>
          <span class="p-input-icon-left">
            <i class="pi pi-send"/>
              <InputText id="telegram"
                         type="text"
                         placeholder="Телеграм"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.telegram"
              />
            </span>
          <span v-if="v$.telegram?.$errors[0]?.$message" class="text-red-400">
                {{ v$.telegram?.$errors[0]?.$message }}
              </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="championId">Champion Id</label>
          <span class="p-input-icon-left">
            <i class="pi pi-id-card"/>
              <InputText id="championId"
                         type="number"
                         placeholder="Champion Id"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.championId"
              />
            </span>
          <span v-if="v$.championId?.$errors[0]?.$message" class="text-red-400">
            {{ v$.championId?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="championLogin">Champion Login</label>
          <span class="p-input-icon-left">
            <i class="pi pi-user"/>
              <InputText id="championLogin"
                         type="text"
                         placeholder="Champion Login"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.championLogin"
              />
            </span>
          <span v-if="v$.championLogin?.$errors[0]?.$message" class="text-red-400">
                {{ v$.championLogin?.$errors[0]?.$message }}
              </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center ">
          <label for="bonusBalance">Бонусный баланс</label>
          <span class="p-input-icon-left">
            <i class="pi pi-wallet"/>
              <InputText id="bonusBalance"
                         type="number"
                         placeholder="Бонусный баланс"
                         style="padding: 1rem; padding-left: 3rem; width: 100%"
                         v-model="partnerFieldsData.bonusBalance"
              />
          </span>
          <span v-if="v$.bonusBalance?.$errors[0]?.$message" class="text-red-400">
            {{ v$.bonusBalance?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">

        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
            <div class="flex mr-2">
              <Button label="Отменить"
                      severity="danger"
                      icon="pi pi-directions-alt"
                      text
                      @click="router.back()"
              />
            </div>
            <div class="flex">
              <Button label="Сохранить"
                      type="submit"
                      icon="pi pi-save"
                      severity="success"
                      text
              />
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

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
