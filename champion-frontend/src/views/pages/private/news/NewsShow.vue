<script setup lang="ts">
import { computed, onMounted, reactive, ref, toRefs, watchEffect } from 'vue'
import ProgressBar from 'primevue/progressbar'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import router from '@/router'
import Toast from 'primevue/toast'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import ConfirmDialog from 'primevue/confirmdialog'
import useVuelidate from '@vuelidate/core'
import { required } from '@/i18n/i18n-validators'
import Image from 'primevue/image'
import Textarea from 'primevue/textarea'
import { News } from '@/types/News'

const confirm = useConfirm()
const toast = useToast()
const news = ref<News | null>(null)
const loading = ref(false)
const isSaveDisabled = ref(true)
const chooseFileComponent = ref(null)

const rules = computed(() => {
  return {
    title: { required },
    text: { required },
    newsImg: { required }
  }
})
const newsFieldsData = reactive({
  id: null,
  title: '',
  text: '',
  newsImg: '',
  showConfirm: false
})

onMounted(() => {
  loading.value = true
  setTimeout(() => {
    ;(news.value = {
      id: 1,
      title: 'Новсть 1 Новсть 1 Новсть 1 Новсть 1 Новсть 1',
      text: 'Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст Новсть 1 текст',
      newsImg:
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSAO3VamUZJCAXcGJHTyJLWKb0NvMTbO-dcg&usqp=CAU'
    }),
      (newsFieldsData.id = news.value.id),
      (newsFieldsData.title = news.value.title),
      (newsFieldsData.text = news.value.text),
      (newsFieldsData.newsImg = news.value.newsImg),
      (loading.value = false)
  }, 100)
})
const requireConfirmation = () => {
  confirm.require({
    group: 'headless',
    header: 'Уверены, что хотите удалить новость?',
    message: 'Пожалуйста, подтвердите действие.',
    accept: () => {
      toast.add({ severity: 'success', summary: 'Новость успешно удалена', life: 3000 })
    }
  })
}
const getIsSaveDisabled = (): boolean => {
  if (!newsFieldsData.title) {
    return true
  }
  if (!newsFieldsData.text) {
    return true
  }
  if (!newsFieldsData.newsImg) {
    return true
  }

  if (
    JSON.stringify(news.value) ===
    JSON.stringify({
      title: newsFieldsData.title,
      text: newsFieldsData.text,
      newsImg: newsFieldsData.newsImg
    })
  ) {
    return true
  }

  return false
}

const v$ = useVuelidate(rules, toRefs(newsFieldsData))

const onUpload = (e) => {
  const photo = e.target.files[0]
  newsFieldsData.newsImg = URL.createObjectURL(photo)

  toast.add({ severity: 'info', summary: 'Success', detail: 'Фото загружено', life: 2000 })
}

const openFileUpload = () => {
  chooseFileComponent.value.click()
}

const onSubmit = async () => {
  loading.value = true

  try {
    const isValid = await v$.value.$validate()
    console.log(newsFieldsData)
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
      detail: 'Новость успешно изменена.',
      life: 3000
    })
    setTimeout(() => {
      loading.value = false
      router.push('/admin/news')
    }, 1000)
  } catch {
    toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
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
          <Button label="Удалить" @click="acceptCallback"></Button>
          <Button label="Отменить" outlined @click="rejectCallback"></Button>
        </div>
      </div>
    </template>
  </ConfirmDialog>

  <div class="card" style="height: calc(100vh - 9rem); overflow: auto">
    <ProgressBar v-if="!news" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Новость №{{ newsFieldsData.id }}:</h3>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="p-2 flex flex-column align-items-start justify-content-center lg:w-12">
          <label for="imgUrl">Изображение</label>
          <Image
            :src="newsFieldsData.newsImg"
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
          />
          <span v-if="v$.newsImg?.$errors[0]?.$message" class="text-red-400">
            {{ v$.newsImg?.$errors[0]?.$message }}
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
              v-model="newsFieldsData.text"
            />
          </span>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div class="flex mr-2">
            <Button label="Отменить" icon="pi pi-directions-alt" text @click="router.back()" />
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
