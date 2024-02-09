<script setup lang="ts">
import {computed, onMounted, reactive, ref, toRefs, watchEffect} from 'vue';
import ProgressBar from 'primevue/progressbar';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import router from '@/router';
import Toast from 'primevue/toast';
import {useConfirm} from 'primevue/useconfirm';
import {useToast} from 'primevue/usetoast';
import ConfirmDialog from 'primevue/confirmdialog';
import useVuelidate from '@vuelidate/core';
import {required, minValue, numeric} from '@/i18n/i18n-validators';
import {Product} from '@/types/Products';
import Image from 'primevue/image';
import Dropdown from 'primevue/dropdown';
import RadioButton from 'primevue/radiobutton';
import Tag from 'primevue/tag';
import Textarea from 'primevue/textarea';


const confirm = useConfirm();
const toast = useToast();
const product = ref<Product | null>(null);
const loading = ref(false);
const isSaveDisabled = ref(true);
const chooseFileComponent = ref(null);

const rules = computed(() => {
  return {
    name: {required},
    imgUrl: {required},
    price: {required, numeric, minValue: minValue(1)},
    categoryId: {required, numeric, minValue: minValue(1)},
  };

});
const productFieldsData = reactive({
  id: null,
  name: '',
  description: '',
  price: null,
  active: false,
  categoryId: null,
  imgUrl: '',
  showConfirm: false
});
const categories = ref<{ name: String, code: Number }[] | null>(null);

onMounted(() => {
  loading.value = true;
  setTimeout(() => {
    product.value = {
      id: 1,
      name: 'Товар 1',
      description: 'Описание товара 1 Описание товара 1 Описание товара 1',
      price: 500,
      active: true,
      categoryId: 1,
      imgUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRB6p_1WriDjdY2v6Y5RXYkVlmtqAMAVIOBTw&usqp=CAU'
    },
        productFieldsData.id = product.value.id,
        productFieldsData.name = product.value.name,
        productFieldsData.description = product.value.description,
        productFieldsData.price = product.value.price,
        productFieldsData.active = product.value.active,
        productFieldsData.categoryId = product.value.categoryId,
        productFieldsData.imgUrl = product.value.imgUrl;

    categories.value = [
      {name: 'Электроника', code: 1},
      {name: 'Одежда', code: 2},
      {name: 'Транспорт', code: 3},
      {name: 'Аксесуары', code: 4},
    ];
    loading.value = false;
  }, 100);
});
const requireConfirmation = () => {
  confirm.require({
    group: 'headless',
    header: product.value?.active ? 'Уверены, что хотите деакивировать товар?' : 'Уверены, что хотите акивировать товар?',
    message: 'Пожалуйста, подтвердите действие.',
    accept: () => {
      toast.add({severity: 'success', summary: 'Пароль успешно сброшен', life: 3000});
    },
  });
};
const getIsSaveDisabled = (): boolean => {

  if (!productFieldsData.name) {
    return true;
  }
  if (!productFieldsData.price) {
    return true;
  }
  if (!productFieldsData.categoryId) {
    return true;
  }
  if (!productFieldsData.imgUrl) {
    return true;
  }

  if (JSON.stringify(product.value) === JSON.stringify({
    id: productFieldsData.id,
    name: productFieldsData.name,
    email: productFieldsData.description,
    telegram: productFieldsData.price,
    championId: productFieldsData.active,
    championLogin: productFieldsData.categoryId,
    bonusBalance: productFieldsData.imgUrl,
  })) {
    return true;
  }

  return false;
};

const v$ = useVuelidate(rules, toRefs(productFieldsData));

const onUpload = (e) => {
  const photo = e.target.files[0];
  productFieldsData.imgUrl = URL.createObjectURL(photo);

  toast.add({severity: 'info', summary: 'Success', detail: 'Фото загружено', life: 2000});
};

const openFileUpload = () => {
  chooseFileComponent.value.click();
};

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
    toast.add({severity: 'success', summary: 'Confirmed', detail: 'Товар успешно изменен.', life: 3000});
    setTimeout(() => {
      loading.value = false;
      router.push('/admin/shop');
    }, 1000);
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
    loading.value = false;
  }
};

watchEffect(() => {
  isSaveDisabled.value = getIsSaveDisabled();
});

</script>

<template>
  <Toast/>
  <ConfirmDialog group="headless">
    <template #container="{ message, acceptCallback, rejectCallback }">
      <div class="flex flex-column align-items-center p-5 surface-overlay border-round">
        <div class="border-circle bg-primary inline-flex justify-content-center align-items-center h-6rem w-6rem -mt-8">
          <i class="pi pi-question text-5xl"></i>
        </div>
        <span class="font-bold text-2xl block mb-2 mt-4">{{ (message as any).message }}</span>
        <p class="mb-0">{{ (message as any).header }}</p>
        <div class="flex align-items-center gap-2 mt-4">
          <Button :label="product.active ? 'Деактивировать' : 'Активировать'" @click="acceptCallback"></Button>
          <Button label="Отменить" outlined @click="rejectCallback"></Button>
        </div>
      </div>
    </template>
  </ConfirmDialog>

  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="!product || !categories" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Детали товара {{ productFieldsData.name }}:</h3>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">
          <label for="imgUrl">Изображение</label>
          <Image
              :src="productFieldsData.imgUrl"
              alt="Image"
              width="200"
              class="cursor-pointer"
              @click="openFileUpload"
          />
          <input type="file" @change="onUpload" name="imgUrl" ref="chooseFileComponent" style="display: none"/>
          <span v-if="v$.imgUrl?.$errors[0]?.$message" class="text-red-400">
                {{ v$.imgUrl?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center ">

        </div>
      </div>

      <div class="flex border-round align-items-start inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="name">Название</label>
          <span class="p-input-icon-left">
              <InputText id="name"
                         type="text"
                         placeholder="Название товара"
                         style="padding: 1rem; width: 100%"
                         v-model="productFieldsData.name"
              />
            </span>
          <span v-if="v$.name?.$errors[0]?.$message" class="text-red-400">
            {{ v$.name?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="categoryId">Категория</label>
          <span class="p-input-icon-left">
            <Dropdown v-model="productFieldsData.categoryId"
                      :options="categories"
                      optionLabel="name"
                      optionValue="code"
                      style="padding: 6px; width: 100%"
            />
            </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="price">Цена</label>
          <span class="p-input-icon-left">
              <InputText id="price"
                         type="number"
                         inputmode="numeric"
                         placeholder="Цена"
                         style="padding: 1rem; width: 100%"
                         v-model="productFieldsData.price"
              />
            </span>
          <span v-if="v$.price?.$errors[0]?.$message" class="text-red-400">
                {{ v$.price?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <div class="lg:w-12 p-2 flex align-items-center justify-content-start ">
            <label for="status" class="ml-1 mr-2">Статус:</label>
            <div class="flex flex-wrap gap-3">
              <div class="flex align-items-center">
                <RadioButton v-model="productFieldsData.active" inputId="active" name="active" :checked="productFieldsData.active" :value="true"/>
                <label for="active" class="ml-2">
                  <Tag severity="success" value="Активен"/>
                </label>
              </div>
              <div class="flex align-items-center">
                <RadioButton v-model="productFieldsData.active" inputId="inActive" name="active" :checked="!productFieldsData.active" :value="false" />
                <label for="inActive" class="ml-2">
                  <Tag severity="danger" value="Не активен"/>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="description">Описание товара</label>
          <span class="p-input-icon-left">
              <Textarea id="description"
                        rows="6"
                        type="text"
                        placeholder="Описание товара"
                        style="padding: 1rem; width: 100%"
                        v-model="productFieldsData.description"
              />
          </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div v-if="product.active" class="flex mr-2">
            <Button label="Деактивировать"
                    icon="pi pi-times"
                    text
                    @click="requireConfirmation()"
            />
          </div>
          <div v-else class="flex mr-2">
            <Button label="Активировать"
                    icon="pi pi-check"
                    text
                    @click="requireConfirmation()"
            />
          </div>
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
                    :disabled="isSaveDisabled"
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
