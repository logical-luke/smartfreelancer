<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import ProjectItem from "@/components/project/ProjectItem.vue";

const { t } = useI18n();
</script>

<template>
  <div class="flex mb-8">
    <h3 class=" text-2xl font-bold">{{ t("Projects") }}</h3>
  </div>
  <transition name="slide">
    <div class="flex container flex-wrap gap-8 mb-8">
      <transition-group name="slide">
        <ProjectItem />
      </transition-group>
    </div>
  </transition>
</template>
