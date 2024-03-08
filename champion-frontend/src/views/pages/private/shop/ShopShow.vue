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
import {useRoute, useRouter} from 'vue-router';
import {isString} from 'lodash';
import {
  deleteProduct,
  getProduct,
  productBindImage,
  toggleProductStatus,
  updateProduct
} from '@/http/shop/ShopServices';
import {getCategories} from '@/http/categories/CategoriesServices';
import type {CreateProductRequest} from '@/types/requests/shop/Product';
import {asset} from '@/helpers/StaticHelper';

const confirm = useConfirm();
const navigate = useRouter();
const toast = useToast();
const route = useRoute();

const product = ref<Product | null>(null);
const categories = ref<{ name: string, code: number }[] | null>(null);
const loading = ref(false);
const isSaveDisabled = ref(true);
const chooseFileComponent = ref(null);
const id = route.params.id;

const rules = computed(() => {
  return {
    name: {required},
    image: {required},
    price: {required, numeric, minValue: minValue(1)},
    category: {required},
  };
});
const productFieldsData = reactive({
  name: '',
  description: '',
  price: null,
  status: null,
  category: null,
  imgUrl: '',
  image: null,
  showConfirm: false
});

onMounted(async () => {
  loading.value = true;

  try {
    const res = id && (await getProduct(id as string));
    const categoryList = await getCategories();

    if (!res || !categoryList) {
      toast.add({severity: 'error', summary: 'Ошибка', detail: 'Загрузить данные не удалось.', life: 3000});
      navigate.back();
      return
    }

    categories.value = categoryList.map(category => {
      return {name: category.name, code: category.id};
    });

    if (!isString(res)) {
      product.value = res as Product;
      productFieldsData.name = (res as Product).name;
      productFieldsData.description = (res as Product).description;
      productFieldsData.price = (res as Product).price;
      productFieldsData.status = (res as Product).status;
      productFieldsData.category = (res as Product).category.id;
      productFieldsData.image = (res as Product).image;
    } else {
      toast.add({severity: 'error', summary: 'Ошибка', detail: res, life: 3000});
      navigate.back();
    }
  }
  catch {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Не удалось загрузить данные. Попробуйте еще раз.',
      life: 3000
    });
    navigate.back();
  }
  finally {
    loading.value = false;
  }
});
const requireConfirmation = () => {
  confirm.require({
    group: 'headless',
    header: `Вы действительно хотите удалить данный товар? Пожалуйста, подтвердите действие.`,
    message: 'Внимание!',
    accept: () => {
      deleteHandler();
    }
  });
};

const deleteHandler = async () => {
  loading.value = true;
  try {
    const res = await deleteProduct(Number(id));
    if (res === 204) {
      toast.add({
        severity: 'success',
        summary: 'Готово',
        detail: 'Товар ушспешно удален.',
        life: 3000
      });
      await router.push('/admin/shop')
    }
    else {
      toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
    }
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
  }
  finally {
    loading.value = false;
  }
};

const getIsSaveDisabled = (): boolean => {
  if (!productFieldsData.name) {
    return true;
  }
  if (!productFieldsData.price) {
    return true;
  }
  if (!productFieldsData.category) {
    return true;
  }
  if (!productFieldsData.image) {
    return true;
  }

  if (
      JSON.stringify({
        name: product.value.name,
        description: product.value.description,
        price: product.value.price,
        status: product.value.status,
        category: product.value.category.id,
        image: product.value.image
      }) ===
      JSON.stringify({
        name: productFieldsData.name,
        description: productFieldsData.description,
        price: productFieldsData.price,
        status: productFieldsData.status,
        category: productFieldsData.category,
        image: productFieldsData.image
      })
  ) {
    return true;
  }

  return false;
};

const v$ = useVuelidate(rules, toRefs(productFieldsData));

const onUpload = (e) => {
  productFieldsData.image = e.target.files[0];
  productFieldsData.imgUrl = URL.createObjectURL(e.target.files[0]);

  toast.add({severity: 'info', summary: 'Готово', detail: 'Фото загружено', life: 2000});
};

const openFileUpload = () => {
  chooseFileComponent.value.click();
};

const toggleHandler = async () => {
  loading.value = true;
  try {
    const res = await toggleProductStatus(id as string);

    if ((res as Product).id) {
      product.value = res as Product
      toast.add({
        severity: 'success',
        summary: 'Готово',
        detail: !(res as Product).status
            ? 'Продукт успешно деактивирован.'
            : 'Продукт успешно активирован.',
        life: 3000
      });
    }
    else {
      toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
    }
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
  }
  finally {
    loading.value = false;
  }
};

const onSubmit = async () => {
  loading.value = true;

  const updateRequest: CreateProductRequest = {
    name: productFieldsData.name,
    description: productFieldsData.description,
    price: Number(productFieldsData.price),
    status: productFieldsData.status,
    category: Number(productFieldsData.category)
  };

  try {
    const isValid = await v$.value.$validate();
    if (!isValid) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Проверьте введенные данные.',
        life: 3000
      });
      loading.value = false;
      return;
    }
    else {
      const updateProductRes = await updateProduct(id as string, updateRequest);

      if (isString(updateProductRes)) {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: updateProductRes,
          life: 3000
        });
        return;
      }

      if (updateProductRes) {
        const pickUpload = await productBindImage((updateProductRes as Product).id, productFieldsData.image);
        toast.add({
          severity: 'success',
          summary: 'Готово',
          detail: 'Товар успешно изменен.',
          life: 3000
        });

        !pickUpload.id && toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: 'Не удалось обновить изображение товара.',
          life: 3000
        });

        await router.push('/admin/shop');
      }
      else {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: 'Попробуйте еще раз.',
          life: 3000
        });
      }
    }
  }
  catch {
    toast.add({severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000});
  }
  finally {
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
        <div
            class="border-circle bg-primary inline-flex justify-content-center align-items-center h-6rem w-6rem -mt-8"
        >
          <i class="pi pi-question text-5xl"></i>
        </div>
        <span class="font-bold text-2xl block mb-2 mt-4">{{ (message as any).message }}</span>
        <p class="mb-0">{{ (message as any).header }}</p>
        <div class="flex align-items-center gap-2 mt-4">
          <Button label="Отменить" outlined @click="rejectCallback"/>
          <Button label="Удалиь" @click="acceptCallback" />
        </div>
      </div>
    </template>
  </ConfirmDialog>

  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar
        v-if="!product || !categories"
        mode="indeterminate"
        style="height: 6px"
    ></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Детали товара {{ productFieldsData.name }}:</h3>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">
          <label for="imgUrl">Изображение</label>
          <Image
              :src="
              productFieldsData.imgUrl
                ? productFieldsData.imgUrl
                : asset(productFieldsData.image)
            "
              alt="Image"
              width="200"
              class="cursor-pointer"
              @click="openFileUpload"
          />
          <input
              type="file"
              @change="onUpload"
              name="imgUrl"
              ref="chooseFileComponent"
              style="display: none"
              accept="image/jpeg, image/png, image/jpg"
          />
          <span v-if="v$.image?.$errors[0]?.$message" class="text-red-400">
            {{ v$.image?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center"></div>
      </div>

      <div class="flex border-round align-items-start inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="name">Название</label>
          <span class="p-input-icon-left">
            <InputText
                id="name"
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
            <Dropdown
                v-model="productFieldsData.category"
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
            <InputText
                id="price"
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
          <div class="lg:w-12 p-2 flex align-items-center justify-content-start">
            <label for="status" class="ml-1 mr-2">Статус:</label>
            <div class="flex flex-wrap gap-3">
              <div class="flex align-items-center">
                <RadioButton
                    v-model="productFieldsData.status"
                    inputId="active"
                    name="active"
                    :checked="productFieldsData.status"
                    :value="true"
                />
                <label for="active" class="ml-2">
                  <Tag severity="success" value="Активен"/>
                </label>
              </div>
              <div class="flex align-items-center">
                <RadioButton
                    v-model="productFieldsData.status"
                    inputId="inActive"
                    name="active"
                    :checked="!productFieldsData.status"
                    :value="false"
                />
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
            <Textarea
                id="description"
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
          <div class="flex mr-2">
            <Button
                label="Назад"
                severity="info"
                icon="pi pi-directions-alt"
                text
                @click="router.back()"
            />
          </div>
          <div v-if="product.status" class="flex mr-2">
            <Button label="Деактивировать" icon="pi pi-times" text @click="toggleHandler"/>
          </div>
          <div v-else class="flex mr-2">
            <Button label="Активировать" icon="pi pi-check" text @click="toggleHandler"/>
          </div>
          <div class="flex mr-2">
            <Button
                label="Удалить"
                severity="danger"
                icon="pi pi-directions-alt"
                text
                @click="requireConfirmation"
            />
          </div>
          <div class="flex">
            <Button
                label="Сохранить"
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

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
