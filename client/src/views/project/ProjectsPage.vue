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
        <ProjectItem
          id="test"
          name="Test Project"
          description="This is a test project"
          client="Test Client"
          :blocked-tasks=0
          :completed-tasks=0
          due-date="2024-12-31"
          :in-progress-tasks=0
          :todo-tasks=0
        />
      </transition-group>
    </div>
  </transition>
</template>
