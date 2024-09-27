<script setup lang="ts">
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
  <div class="flex mb-8">
    <h3 class=" text-2xl font-bold">{{ t("Deep Work Hub") }}</h3>
  </div>
  <div class="font-medium flex flex-wrap mt-4">
    {{ t("There are no more scheduled tasks for today") }}.
    {{ t("Enjoy your spare time") }} 🥳
  </div>
</template>
