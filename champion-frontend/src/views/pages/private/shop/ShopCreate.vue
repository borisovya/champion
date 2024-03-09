<script setup lang="ts">
import { computed, onMounted, reactive, ref, toRefs } from 'vue'
import ProgressBar from 'primevue/progressbar'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import router from '@/router'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import useVuelidate from '@vuelidate/core'
import { minValue, numeric, required } from '@/i18n/i18n-validators'
import Image from 'primevue/image'
import Dropdown from 'primevue/dropdown'
import RadioButton from 'primevue/radiobutton'
import Tag from 'primevue/tag'
import Textarea from 'primevue/textarea'
import { createProduct, productBindImage } from '@/http/shop/ShopServices'
import type { CreateProductRequest } from '@/types/requests/shop/Product'
import { getCategories } from '@/http/categories/CategoriesServices'
import type { Product } from '@/types/Products'
import { isString } from 'lodash'

const toast = useToast()
const loading = ref(false)
const chooseFileComponent = ref(null)

const rules = computed(() => {
  return {
    name: { required },
    image: { required },
    price: { required, numeric, minValue: minValue(1) },
    category: { required }
  }
})
const productCreateFieldsData = reactive({
  name: '',
  description: '',
  price: null,
  status: true,
  category: null,
  image: null,
  imgUrl: ''
})
const categories = ref<{ name: string; code: number }[] | null>(null)

onMounted(async () => {
  loading.value = true
  const categoryList = await getCategories()
  if (categoryList) {
    categories.value = categoryList.map((category) => {
      return { name: category.name, code: category.id }
    })
  }
  loading.value = false
})

const v$ = useVuelidate(rules, toRefs(productCreateFieldsData))

const setImg = (e) => {
  productCreateFieldsData.image = e.target.files[0]
  productCreateFieldsData.imgUrl = URL.createObjectURL(e.target.files[0])

  toast.add({ severity: 'info', summary: 'Готово', detail: 'Фото загружено', life: 2000 })
}

const openFileUpload = () => {
  chooseFileComponent.value.click()
}

const onSubmit = async () => {
  loading.value = true

  try {
    const createProductRequest: CreateProductRequest = {
      name: productCreateFieldsData.name,
      description: productCreateFieldsData.description,
      status: productCreateFieldsData.status,
      price: Number(productCreateFieldsData.price),
      category: Number(productCreateFieldsData.category)
    }
    const isValid = await v$.value.$validate()
    if (!isValid) {
      toast.add({
        severity: 'error',
        summary: 'Ошибка',
        detail: 'Проверьте введенные данные.',
        life: 3000
      })
      loading.value = false
      return
    } else {
      const createProductRes = await createProduct(createProductRequest)

      if (isString(createProductRes)) {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: 'Попробуйте еще раз.',
          life: 3000
        })
        return
      }

      if (createProductRes) {
        await productBindImage((createProductRes as Product).id, productCreateFieldsData.image)
        toast.add({
          severity: 'success',
          summary: 'Confirmed',
          detail: 'Товар успешно добавлен.',
          life: 3000
        })
        await router.push('/admin/shop')
      } else {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: 'Попробуйте еще раз.',
          life: 3000
        })
      }
    }
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <Toast />
  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="loading" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Добавление товара:</h3>

      <div class="lg:flex border-round inputBlocksPaddingTop max-h-20">
        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">
          <label for="imgUrl" class="mb-2">Изображение</label>
          <Image
            v-if="productCreateFieldsData.imgUrl"
            :src="productCreateFieldsData.imgUrl"
            alt="productImage"
            width="200"
            class="cursor-pointer"
            @click="openFileUpload"
          />
          <Button
            label="Выбрать изображение"
            icon="pi pi-cloud-upload"
            @click="openFileUpload"
            class="max-w-16rem p-3"
          />
          <input
            type="file"
            @change="setImg"
            name="image"
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

      <div class="lg:flex border-round align-items-start inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="name">Название</label>
          <span class="p-input-icon-left">
            <InputText
              id="name"
              type="text"
              placeholder="Название товара"
              style="padding: 1rem; width: 100%"
              v-model="productCreateFieldsData.name"
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
              v-model="productCreateFieldsData.category"
              :options="categories"
              optionLabel="name"
              optionValue="code"
              style="padding: 6px; width: 100%"
            />
          </span>

          <span v-if="v$.category?.$errors[0]?.$message" class="text-red-400">
            {{ v$.category?.$errors[0]?.$message }}
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
              v-model="productCreateFieldsData.price"
            />
          </span>
          <span v-if="v$.price?.$errors[0]?.$message" class="text-red-400">
            {{ v$.price?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <div class="lg:w-12 p-2 flex align-items-center justify-content-start">
            <label for="status" class="ml-1 mr-2">Статус товара:</label>
            <div class="flex flex-wrap gap-3">
              <div class="flex align-items-center">
                <RadioButton
                  v-model="productCreateFieldsData.status"
                  inputId="active"
                  name="status"
                  :checked="productCreateFieldsData.status"
                  :value="true"
                />
                <label for="active" class="ml-2">
                  <Tag severity="success" value="Активен" />
                </label>
              </div>
              <div class="flex align-items-center">
                <RadioButton
                  v-model="productCreateFieldsData.status"
                  inputId="inActive"
                  name="status"
                  :checked="!productCreateFieldsData.status"
                  :value="false"
                />
                <label for="inActive" class="ml-2">
                  <Tag severity="danger" value="Не активен" />
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
              v-model="productCreateFieldsData.description"
            />
          </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div class="flex mr-2">
            <Button
              label="Отменить"
              severity="danger"
              icon="pi pi-directions-alt"
              text
              @click="router.back()"
            />
          </div>
          <div class="flex">
            <Button label="Сохранить" type="submit" icon="pi pi-save" severity="success" text />
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
