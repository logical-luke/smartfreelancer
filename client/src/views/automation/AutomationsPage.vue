<script setup lang="ts">
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
  <div class="flex mb-8">
    <h3 class="text-xl font-bold">{{ t("Automations") }}</h3>
  </div>
</template>

<style scoped></style>
