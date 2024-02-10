<script setup lang="ts">
import {computed, onMounted, reactive, ref, toRefs} from 'vue';
import ProgressBar from 'primevue/progressbar';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import router from '@/router';
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';
import useVuelidate from '@vuelidate/core';
import {minValue, numeric, required} from '@/i18n/i18n-validators';
import Image from 'primevue/image';
import Dropdown from 'primevue/dropdown';
import RadioButton from 'primevue/radiobutton';
import Tag from 'primevue/tag';
import Textarea from 'primevue/textarea';

const toast = useToast();
const loading = ref(false);

const rules = computed(() => {
  return {
    name: {required},
  };

});
const shopCategoryFieldsData = reactive({
  name: '',
  active: true,
});

const v$ = useVuelidate(rules, toRefs(shopCategoryFieldsData));

const onSubmit = async () => {
  loading.value = true;

  try {
    const isValid = await v$.value.$validate();
    console.log(shopCategoryFieldsData);
    if (!isValid) {
      toast.add({severity: 'error', summary: 'Ошибка', detail: 'Проверьте введенные данные.', life: 3000});
      loading.value = false;
      return;
    }
    toast.add({severity: 'success', summary: 'Confirmed', detail: 'Категория успешно добавлена.', life: 3000});
    setTimeout(() => {
      loading.value = false;
      router.push('/admin/shop/categories');
    }, 1000);
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
    loading.value = false;
  }
};

</script>

<template>
  <Toast/>
  <div class="card" style="height: calc(100vh - 9rem); overflow: auto;">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Добавление категории:</h3>

      <div class="lg:flex border-round align-items-start inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="name">Название категории</label>
          <span class="p-input-icon-left">
              <InputText id="name"
                         type="text"
                         placeholder="Название категории"
                         style="padding: 1rem; width: 100%"
                         v-model="shopCategoryFieldsData.name"
              />
            </span>
          <span v-if="v$.name?.$errors[0]?.$message" class="text-red-400">
            {{ v$.name?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">

        </div>
      </div>

      <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
        <div class="lg:w-12 p-2 flex align-items-center justify-content-start ">
          <label for="status" class="ml-1 mr-2">Статус:</label>
          <div class="flex flex-wrap gap-3">
            <div class="flex align-items-center">
              <RadioButton v-model="shopCategoryFieldsData.active" inputId="active" name="active"
                           :checked="shopCategoryFieldsData.active" :value="true"/>
              <label for="active" class="ml-2">
                <Tag severity="success" value="Активен"/>
              </label>
            </div>
            <div class="flex align-items-center">
              <RadioButton v-model="shopCategoryFieldsData.active" inputId="inActive" name="active"
                           :checked="!shopCategoryFieldsData.active" :value="false"/>
              <label for="inActive" class="ml-2">
                <Tag severity="danger" value="Не активен"/>
              </label>
            </div>
          </div>
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

@media (max-width: 992px) {
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
