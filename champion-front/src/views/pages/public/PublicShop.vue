<script setup lang="ts">
import Button from 'primevue/button';
import {useRouter} from 'vue-router';

const router = useRouter();

const onButtonClickHandler = () => {
  router.push('/admin');
};
</script>

<template>
  <h1>Скоро тут будет магаз, а пока кликай по кнопке</h1>

  <Button label="Перейти в админ панель"
          @click="onButtonClickHandler"
          class="w-auto p-3 text-xl border-round-3xl"
          style="background: linear-gradient(270deg,#ef008f 0.26%,#a815ed 99.72%); border: none;">
  </Button>
</template>
