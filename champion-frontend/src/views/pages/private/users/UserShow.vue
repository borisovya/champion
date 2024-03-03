<script setup lang="ts">
import { computed, onMounted, reactive, ref, toRefs, watchEffect } from 'vue'
import type { Partner } from '@/types/Partner'
import ProgressBar from 'primevue/progressbar'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import router from '@/router'
import Textarea from 'primevue/textarea'
import Dialog from 'primevue/dialog'
import Toast from 'primevue/toast'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import ConfirmDialog from 'primevue/confirmdialog'
import useVuelidate from '@vuelidate/core'
import { email, minValue, numeric, required } from '@/i18n/i18n-validators'
import { getPartner, updatePartner } from '@/http/partners/PartnersServices'
import { useRoute, useRouter } from 'vue-router'
import { isString } from 'lodash'
import Dropdown from 'primevue/dropdown'
import { Roles } from '@/enum/Roles'
import type { User } from '@/types/User'
import { useUserStore } from '@/store/useStore'

const confirm = useConfirm()
const navigate = useRouter()
const route = useRoute()
const toast = useToast()

const id = route.params.id
const partner = ref<Partner | string | null>(null)
const loading = ref(false)
const isSaveDisabled = ref(true)
const userStore = useUserStore()
const accountData = userStore.getUser()

const roleOptions = ref([
  { name: 'Главный администратор', code: [Roles.SUPER_ADMIN] },
  { name: 'Администратор', code: [Roles.ADMIN] },
  { name: 'Партнер', code: [Roles.USER] }
])

const rules = computed(() => {
  return {
    username: { required, email },
    telegramLogin: { required },
    championPartnersLogin: { required },
    balance: { required, numeric, minValue: minValue(0) },
    roles: { required }
  }
})
const partnerFieldsData = reactive({
  id: Number(id),
  name: '',
  username: '',
  telegramLogin: '',
  championPartnersLogin: '',
  balance: null,
  roles: null,
  messageForPartner: '',
  showMessageModal: false,
  showResetPasswordModal: false,
  userIdentifier: ''
})

onMounted(async () => {
  loading.value = true

  try {
    const res = id && (await getPartner(id as string))

    if (!isString(res)) {
      partner.value = res as Partner

      partnerFieldsData.id = (res as Partner).id
      partnerFieldsData.username = (res as Partner).username
      partnerFieldsData.telegramLogin = (res as Partner).telegramLogin
      partnerFieldsData.championPartnersLogin = (res as Partner).championPartnersLogin
      partnerFieldsData.roles = (res as Partner).roles
      partnerFieldsData.balance = (res as Partner).balance
      partnerFieldsData.userIdentifier = (res as Partner).userIdentifier
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
    header: 'Уверены, что хотите сбросить пароль?',
    message: 'Пожалуйста, подтвердите действие.',
    accept: () => {
      toast.add({ severity: 'success', summary: 'Пароль успешно сброшен', life: 3000 })
    }
  })
}
const getIsSaveDisabled = (): boolean => {
  if (!partnerFieldsData.username) {
    return true
  }
  if (!partnerFieldsData.telegramLogin) {
    return true
  }
  if (!partnerFieldsData.championPartnersLogin) {
    return true
  }
  if (!partnerFieldsData.balance) {
    return true
  }

  if (
    JSON.stringify(partner.value) ===
    JSON.stringify({
      id: partnerFieldsData.id,
      username: partnerFieldsData.username,
      telegramLogin: partnerFieldsData.telegramLogin,
      championPartnersLogin: partnerFieldsData.championPartnersLogin,
      balance: partnerFieldsData.balance,
      roles: partnerFieldsData.roles,
      userIdentifier: partnerFieldsData.userIdentifier
    })
  ) {
    return true
  }

  return false
}
const messageCancelClickHandler = () => {
  partnerFieldsData.showMessageModal = false
  partnerFieldsData.messageForPartner = ''
}
const messageSendClickHandler = () => {
  partnerFieldsData.showMessageModal = false
  partnerFieldsData.messageForPartner = ''
  toast.add({
    severity: 'success',
    summary: 'Готово',
    detail: 'Сообщение отправлено',
    life: 3000
  })
}

const v$ = useVuelidate(rules, toRefs(partnerFieldsData))

const onSubmit = async () => {
  loading.value = true

  const updateRequest = {
    id: partnerFieldsData.id,
    username: partnerFieldsData.username,
    telegramLogin: partnerFieldsData.telegramLogin,
    championPartnersLogin: partnerFieldsData.championPartnersLogin,
    //balance: partnerFieldsData.balance,
    roles: partnerFieldsData.roles,
    userIdentifier: ''
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
    }

    const res = await updatePartner(updateRequest)

    if (res) {
      toast.add({
        severity: 'success',
        summary: 'Готово',
        detail: 'Данные пользователя успешно изменены.',
        life: 3000
      })

      partner.value = res as Partner
      //await router.push('/admin/shop/categories')
    } else {
      toast.add({ severity: 'error', summary: 'Ошибка', detail: 'Попробуйте еще раз.', life: 3000 })
    }
  } catch (e) {
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
  <Dialog
    v-model:visible="partnerFieldsData.showMessageModal"
    modal
    :header="'Отправка сообщения ' + partnerFieldsData.telegramLogin"
    :style="{ width: '45rem' }"
  >
    <span class="p-text-secondary block mb-3">Текст сообщения:</span>
    <div class="flex align-items-center gap-3 mb-3">
      <Textarea
        id="message"
        rows="7"
        v-model="partnerFieldsData.messageForPartner"
        class="flex-auto"
        autocomplete="off"
      />
    </div>
    <div class="flex justify-content-end gap-2">
      <Button
        type="button"
        label="Отменить"
        severity="secondary"
        @click="messageCancelClickHandler"
      ></Button>
      <Button
        type="button"
        label="Отправить"
        :disabled="!partnerFieldsData.messageForPartner"
        @click="messageSendClickHandler"
      ></Button>
    </div>
  </Dialog>

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
          <Button label="Сбросить" @click="acceptCallback"></Button>
          <Button label="Отменить" outlined @click="rejectCallback"></Button>
        </div>
      </div>
    </template>
  </ConfirmDialog>

  <div class="card" style="min-height: 80vh">
    <ProgressBar v-if="!partner" mode="indeterminate" style="height: 6px"></ProgressBar>

    <form v-else class="p-grid p-fluid p-justify-center" @submit.prevent.stop="onSubmit">
      <h3 class="ml-3">Данные партнера {{ partnerFieldsData.username }}:</h3>

      <div class="lg:flex border-round inputBlocksPaddingBottom">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="email">Email</label>
          <span class="p-input-icon-left">
            <i class="pi pi-at" />
            <InputText
              id="email"
              type="text"
              placeholder="Email"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.username"
            />
          </span>
          <span v-if="v$.username?.$errors[0]?.$message" class="text-red-400">
            {{ v$.username?.$errors[0]?.$message }}
          </span>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="telegram">Телеграм</label>
          <span class="p-input-icon-left">
            <i class="pi pi-send" />
            <InputText
              id="telegram"
              type="text"
              placeholder="Телеграм"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.telegramLogin"
            />
          </span>
          <div v-if="v$.telegramLogin?.$errors[0]?.$message" class="text-red-400">
            {{ v$.telegramLogin?.$errors[0]?.$message }}
          </div>
        </div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="championPartnersLogin">Champion Partners Login</label>
          <span class="p-input-icon-left">
            <i class="pi pi-user" />
            <InputText
              id="championPartnersLogin"
              type="text"
              placeholder="Телеграм"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.championPartnersLogin"
            />
          </span>
          <div v-if="v$.championPartnersLogin?.$errors[0]?.$message" class="text-red-400">
            {{ v$.championPartnersLogin?.$errors[0]?.$message }}
          </div>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="balance">Бонусный баланс</label>
          <span class="p-input-icon-left">
            <i class="pi pi-wallet" />
            <InputText
              id="balance"
              type="number"
              placeholder="Бонусный баланс"
              style="padding: 1rem; padding-left: 3rem; width: 100%"
              v-model="partnerFieldsData.balance"
            />
          </span>
          <div v-if="v$.balance?.$errors[0]?.$message" class="text-red-400">
            {{ v$.balance?.$errors[0]?.$message }}
          </div>
        </div>
      </div>

      <div
        v-if="(accountData as User).roles[0] === Roles.SUPER_ADMIN"
        class="lg:flex border-round inputBlocksPaddingTop"
      >
        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center">
          <label for="role">Роль</label>
          <span class="p-input-icon-left">
            <i class="pi pi-user" />
            <Dropdown
              v-model="partnerFieldsData.roles"
              optionLabel="name"
              :options="roleOptions"
              optionValue="code"
              style="padding: 6px; width: 100%"
            />
          </span>
          <div v-if="v$.roles?.$errors[0]?.$message" class="text-red-400">
            {{ v$.roles?.$errors[0]?.$message }}
          </div>
        </div>

        <div class="lg:w-12 p-2 flex flex-column align-items-start justify-content-center"></div>
      </div>

      <div class="lg:flex border-round inputBlocksPaddingTop">
        <div class="w-12 p-2 flex flex-wrap align-items-start justify-content-start">
          <div class="flex mr-2">
            <Button
              label="Отправить сообщение"
              icon="pi pi-envelope"
              text
              @click="partnerFieldsData.showMessageModal = true"
              :disabled="loading"
            />
          </div>
          <div class="flex mr-2">
            <Button
              label="Сбросить пароль"
              icon="pi pi-key"
              text
              @click="requireConfirmation()"
              :disabled="loading"
            />
          </div>
          <div class="flex mr-2">
            <Button
              label="Назад"
              severity="danger"
              icon="pi pi-directions-alt"
              text
              @click="router.back()"
              :disabled="loading"
            />
          </div>
          <div class="flex">
            <Button
              label="Сохранить"
              type="submit"
              :disabled="isSaveDisabled || loading"
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
