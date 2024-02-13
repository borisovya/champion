<script setup lang="ts">
import {computed, reactive, ref, toRefs} from 'vue';
import ProgressBar from 'primevue/progressbar';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';
import useVuelidate from '@vuelidate/core';
import {required} from '@/i18n/i18n-validators';
import Textarea from 'primevue/textarea';

const toast = useToast()
const loading = ref(false)

const rules = computed(() => {
  return {
    text: { required }
  }
})
const notificationFieldsData = reactive({
  text: ''
})

const v$ = useVuelidate(rules, toRefs(notificationFieldsData))

const onSubmit = async () => {
  loading.value = true

  try {
    const isValid = await v$.value.$validate()
    console.log(notificationFieldsData)
    if (!isValid) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Проверьте введенные данные.',
        life: 3000
      })
      loading.value = false
      return
    }
    toast.add({
      severity: 'success',
      summary: 'Confirmed',
      detail: 'Сообщение успешно отправлено.',
      life: 3000
    })
    setTimeout(() => {
      loading.value = false
      notificationFieldsData.text = ''
      v$.value.$reset()
    }, 1000)
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
    loading.value = false
  }
}
</script>

<template>
  <Toast />

  <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

  <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
    <h3 class="ml-3">Рассылка:</h3>

    <div class="lg:flex border-round inputBlocksPaddingTop">
      <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
        <label for="text">Текст сообщения</label>
        <span class="p-input-icon-left mt-2">
          <Textarea
            id="text"
            rows="10"
            type="text"
            placeholder="Текст сообщения"
            style="padding: 1rem; width: 100%"
            v-model="notificationFieldsData.text"
          />
        </span>
        <span v-if="v$.text?.$errors[0]?.$message" class="text-red-400">
          {{ v$.text?.$errors[0]?.$message }}
        </span>
      </div>
    </div>

    <div class="lg:flex border-round inputBlocksPaddingTop">
      <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
        <div class="flex mr-2">
          <Button
            label="Сбросить"
            severity="danger"
            icon="pi pi-times"
            text
            @click="notificationFieldsData.text = ''"
          />
        </div>
        <div class="flex">
          <Button label="Отправить" type="submit" icon="pi pi-send" severity="success" text />
        </div>
      </div>
    </div>
  </form>
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

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
