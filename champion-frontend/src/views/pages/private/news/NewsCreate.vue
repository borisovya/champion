<script setup lang="ts">
import { computed, reactive, ref, toRefs } from 'vue'
import ProgressBar from 'primevue/progressbar'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import router from '@/router'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import useVuelidate from '@vuelidate/core'
import { minLength, required } from '@/i18n/i18n-validators'
import Image from 'primevue/image'
import Textarea from 'primevue/textarea'
import { createNews, newsBindImage } from '@/http/news/NewsServices'
import type { News } from '@/types/News'

const toast = useToast()
const loading = ref(false)
const chooseFileComponent = ref(null)

const rules = computed(() => {
  return {
    title: { required, minLength: minLength(2) },
    description: { required, minLength: minLength(3) },
    image: { required }
  }
})
const newsFieldsData = reactive({
  title: '',
  description: '',
  image: null,
  newsImage: ''
})

const v$ = useVuelidate(rules, toRefs(newsFieldsData))

const setImg = (e) => {
  newsFieldsData.image = e.target.files[0]
  newsFieldsData.newsImage = URL.createObjectURL(e.target.files[0])

  toast.add({ severity: 'info', summary: 'Готово', detail: 'Фото загружено', life: 2000 })
}

const openFileUpload = () => {
  chooseFileComponent.value.click()
}

const onSubmit = async () => {
  loading.value = true

  const createRequest = {
    title: newsFieldsData.title,
    description: newsFieldsData.description
  }

  try {
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
      const createNewsRes = await createNews(createRequest)

      if (createNewsRes) {
        await newsBindImage((createNewsRes as News).id, newsFieldsData.image)
        toast.add({
          severity: 'success',
          summary: 'Готово',
          detail: 'Новость успешно добавлена.',
          life: 3000
        })

        await router.push('/admin/news')
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
      <h3 class="ml-3">Добавление новости:</h3>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">
          <label for="imgUrl" class="mb-2">Изображение</label>
          <Image
            v-if="newsFieldsData.newsImage"
            :src="newsFieldsData.newsImage"
            alt="Image"
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
          <label for="name">Звголовок</label>
          <span class="p-input-icon-left">
            <InputText
              id="name"
              type="text"
              placeholder="Заголовок"
              style="padding: 1rem; width: 100%"
              v-model="newsFieldsData.title"
            />
          </span>
          <span v-if="v$.title?.$errors[0]?.$message" class="text-red-400">
            {{ v$.title?.$errors[0]?.$message }}
          </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="text">Текст новости</label>
          <span class="p-input-icon-left">
            <Textarea
              id="text"
              rows="10"
              type="text"
              placeholder="Текст новости"
              style="padding: 1rem; width: 100%"
              v-model="newsFieldsData.description"
            />
          </span>
          <span v-if="v$.description?.$errors[0]?.$message" class="text-red-400">
            {{ v$.description?.$errors[0]?.$message }}
          </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div class="flex mr-2">
            <Button label="Отменить" icon="pi pi-directions-alt" text @click="router.back()" />
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
