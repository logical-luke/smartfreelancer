<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import { useRouter } from 'vue-router';
import MainActionButton from "@/components/form/PrimaryActionButton.vue";
const router = useRouter();

const returnToHomepage = () => {
  router.push('/');
};
</script>

<template>
  <div class="flex -mt-12 h-screen p-4 flex-col gap-4 items-center justify-center">
    <h2 class="text-3xl leading-tight md:text-4xl md:leading-tight lg:text-5xl lg:leading-tight font-bold font-heading">
      {{ t("Page not found") }}
    </h2>
    <h3>
      {{ t("The page you are looking for does not exist.") }}
    </h3>
    <MainActionButton
      @click="returnToHomepage"
    >
      {{ t("Return to homepage") }}
    </MainActionButton>
  </div>
</template>
