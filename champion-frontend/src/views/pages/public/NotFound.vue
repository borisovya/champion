<script setup>
import AppConfig from '@/layout/AppConfig.vue';
import {useRouter} from 'vue-router';

const router = useRouter()
</script>

<template>
  <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <div class="flex flex-column align-items-center justify-content-center">
      <div
          style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, #A815EDFF 10%, rgba(33, 150, 243, 0) 30%)">
        <div class="w-full surface-card py-8 px-5 sm:px-8 flex flex-column align-items-center"
             style="border-radius: 53px">
          <img src="/layout/images/ChampionPartners_full_logo.svg" alt="Champion Partners Logo" style="height: 80px"/>
          <span class="text-purple-500 font-bold text-5xl">404</span>
          <h1 class="text-900 font-bold text-3xl lg:text-5xl mb-2">Not Found</h1>
          <div class="text-600 mb-5">Такой страницы нет. но есть много других полезных страниц.</div>
          <router-link to="/" class="w-full flex align-items-center py-5 border-300 border-bottom-1">
                        <span class="flex justify-content-center align-items-center bg-purple-500 border-round"
                              style="height: 3.5rem; width: 3.5rem">
                            <i class="text-50 pi pi-home text-2xl"></i>
                        </span>
            <span class="ml-4 flex flex-column">
                            <span class="text-900 lg:text-xl font-medium mb-0 block">Перейти на главную страницу</span>
                        </span>
          </router-link>
          <div class="w-full flex align-items-center py-5 border-300 border-bottom-1 cursor-pointer" @click="router.back()">
                        <span class="flex justify-content-center align-items-center bg-purple-500 border-round"
                              style="height: 3.5rem; width: 3.5rem">
                            <i class="text-50 pi pi-arrow-left text-2xl"></i>
                        </span>
            <span class="ml-4 flex flex-column">
                            <span class="text-900 lg:text-xl font-medium mb-0 block">Вернуться на предыдущую страницу</span>
                        </span>
          </div>

        </div>
      </div>
    </div>
  </div>
  <AppConfig simple/>
</template>
