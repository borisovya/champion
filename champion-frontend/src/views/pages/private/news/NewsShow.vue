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
import {required} from '@/i18n/i18n-validators';
import Image from 'primevue/image';
import Textarea from 'primevue/textarea';
import {News} from '@/types/News';
import {useRoute, useRouter} from 'vue-router';
import {isString} from 'lodash';
import {deleteNews, getNews, newsBindImage, updateNews} from '@/http/news/NewsServices';
import {asset} from '@/helpers/StaticHelper';

const confirm = useConfirm()
const navigate = useRouter()
const toast = useToast()
const news = ref<News | null>(null)
const loading = ref(false)
const isSaveDisabled = ref(true)
const chooseFileComponent = ref(null)
const route = useRoute()
const id = route.params.id

const rules = computed(() => {
  return {
    title: { required },
    description: { required },
    showConfirm: { required },
    image: { required }
  }
})
const newsFieldsData = reactive({
  title: '',
  description: '',
  image: null,
  newsImage: '',
  showConfirm: false
})

onMounted(async () => {
  loading.value = true

  try {
    const res = id && (await getNews(id as string))

    if (!isString(res)) {
      news.value = res as News
      newsFieldsData.title = (res as News).title
      newsFieldsData.description = (res as News).description
      newsFieldsData.image = (res as News).image
    } else {
      toast.add({ severity: 'error', summary: 'Ошибка', detail: res, life: 3000 })
      navigate.back()
    }
  } catch {
    toast.add({
      severity: 'error',
      summary: 'Ошибка',
      detail: 'Не удалось загрузить данные. Попробуйте еще раз.',
      life: 3000
    })
    navigate.back()
  } finally {
    loading.value = false
  }
})

const requireConfirmation = () => {
  confirm.require({
    group: 'headless',
    header: 'Вы действительно хотите удалить новость? Пожалуйста, подтвердите действие.',
    message: 'Внимание!',
    accept: () => {
      deleteHandler()
    }
  })
}

const deleteHandler = async () => {
  loading.value = true
  try {
    const res = await deleteNews(id as string)
    if (res === 204) {
      navigate.back()
      toast.add({
        severity: 'success',
        summary: 'Готово',
        detail: 'Новость успешно удалена.',
        life: 3000
      })
    } else {
      toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
    }
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
  } finally {
    loading.value = false
  }
}
const getIsSaveDisabled = (): boolean => {
  if (!newsFieldsData.title) {
    return true
  }
  if (!newsFieldsData.description) {
    return true
  }
  if (!newsFieldsData.image) {
    return true
  }

  if (
    JSON.stringify({
      title: news.value.title,
      text: news.value.description,
      newsImg: news.value.image
    } ) ===
    JSON.stringify({
      title: newsFieldsData.title,
      text: newsFieldsData.description,
      newsImg: newsFieldsData.image
    })
  ) {
    return true
  }

  return false
}

const v$ = useVuelidate(rules, toRefs(newsFieldsData))

const onUpload = (e) => {
  newsFieldsData.image = e.target.files[0]
  newsFieldsData.newsImage = URL.createObjectURL(e.target.files[0])

  toast.add({ severity: 'info', summary: 'Готово', detail: 'Фото загружено', life: 2000 })
}

const openFileUpload = () => {
  chooseFileComponent.value.click()
}

const onSubmit = async () => {
  loading.value = true

  const updateRequest = {
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
      const updateNewsRes = await updateNews(id as string, updateRequest)

      if (isString(updateNewsRes)) {
        toast.add({
          severity: 'error',
          summary: 'Ошибка',
          detail: updateNewsRes,
          life: 3000
        })
        return
      }

      if (updateNewsRes) {
        await newsBindImage((updateNewsRes as News).id, newsFieldsData.image)
        toast.add({
          severity: 'success',
          summary: 'Готово',
          detail: 'Новость успешно изменена.',
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

watchEffect(() => {
  isSaveDisabled.value = getIsSaveDisabled()
})
</script>

<template>
  <Toast />
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
          <Button label="Отменить" outlined @click="rejectCallback"></Button>
          <Button label="Удалить" @click="acceptCallback"></Button>
        </div>
      </div>
    </template>
  </ConfirmDialog>

  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="!news" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Новость №{{ id }}:</h3>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">
          <label for="imgUrl">Изображение</label>
          <Image
            :src="
              newsFieldsData.newsImage
                ? newsFieldsData.newsImage
                : asset(newsFieldsData.image)
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
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div class="flex mr-2">
            <Button label="Назад" severity="info" icon="pi pi-directions-alt" text @click="router.back()" />
          </div>
          <div class="flex mr-2">
            <Button
              label="Удалить"
              severity="danger"
              icon="pi pi-times"
              text
              @click="requireConfirmation()"
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
