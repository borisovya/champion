<script setup lang="ts">
import { ref } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import type { Partner } from '@/types/Partner'
import Button from 'primevue/button'
import { FilterMatchMode } from 'primevue/api'
import { useRouter } from 'vue-router'
import { getRoleName } from '@/enum/Roles'

interface Props {
  partners: Partner[] | []
}

const route = useRouter()
const props = defineProps<Props>()
const partnersData = ref(props.partners)

const filters = ref()

const initFilters = () => {
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
  }
}

initFilters()
</script>

<template>
  <div class="flex flex-column">
    <h3>Учетные записи</h3>

    <div v-if="partnersData?.length > 0" class="flex flex-column justify-content-between">
      <DataTable
        :value="partnersData"
        v-model:filters="filters"
        dataKey="id"
        paginator
        :rows="5"
        :rowsPerPageOptions="[5, 10, 20]"
        class="full-height"
        :globalFilterFields="['username', 'telegramLogin', 'championPartnersLogin', 'balance']"
      >
        <template #header>
          <div class="flex grid justify-content-between justify-content-center pt-2">
            <div class="flex justify-content-start col-3 p-0">
              <Button
                outlined
                icon="pi pi-user-plus"
                label="Добавить учетную запись"
                @click="route.push('/admin/partner/create')"
              />
            </div>
            <div class="col-9 p-0">
              <div class="flex justify-content-end">
                <span class="p-input-icon-left ml-4">
                  <i class="pi pi-search" />
                  <InputText v-model="filters['global'].value" placeholder="Введите значение" />
                </span>
              </div>
            </div>
          </div>
        </template>

        <Column
          field="championPartnersLogin"
          header="Champion Partners Login"
          style="min-width: 12rem; height: 60px"
        />
        <Column field="username" header="Email" style="min-width: 12rem" />
        <Column field="telegramLogin" header="Телеграм" style="min-width: 14rem" />
        <Column header="Роль" style="min-width: 14rem">
          <template v-slot:body="{ data }">
            {{ getRoleName((data as Partner).roles[0]) }}
          </template>
        </Column>
        <Column field="balance" header="Бонусный баланс" style="min-width: 12rem" />
        <Column header="" style="min-width: 3rem">
          <template v-slot:header></template>
          <template v-slot:body="{ data }">
            <Button
              text
              rounded
              size="large"
              icon="pi pi-eye"
              severity="secondary"
              @click="route.push(`/admin/partner/show/${data.id}`)"
            ></Button>
          </template>
        </Column>
      </DataTable>
    </div>

    <div v-else class="flex flex-column justify-content-between">
      <div class="flex flex-column justify-content-between">
        <div class="flex justify-content-start p-0">
          <Button
            outlined
            icon="pi pi-plus"
            label="Добавить партнерв"
            @click="route.push('/admin/partner/create')"
          />
        </div>
      </div>
      <div
        class="flex justify-content-center align-items-center"
        style="height: 400px; font-size: 20px"
      >
        <p>Учетные записи партнеров не найдены.</p>
      </div>
    </div>
  </div>
</template>
