<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import Button from 'primevue/button';
import type { Task } from "@/interfaces/Task";
import SectionWrapper from "@/components/SectionWrapper.vue";

const { t } = useI18n();

interface Props {
  currentFocus: Task | null;
  suggestedTask: Task | null;
}

defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:task', task: Task): void;
  (e: 'toggleTimeTracking', task: Task): void;
  (e: 'setCurrentFocus', task: Task): void;
}>();

function updateTask(updatedTask: Task) {
  emit('update:task', updatedTask);
}

function toggleTimeTracking(task: Task) {
  emit('toggleTimeTracking', task);
}
</script>

<template>
  <SectionWrapper :title="t('Tracked Task')" icon="pi pi-play-circle" color="blue">
    <div v-if="currentFocus">
      <TaskItem
        :task="currentFocus"
        @update:task="updateTask"
        @toggleTimeTracking="toggleTimeTracking"
        :clients="[]"
        :projects="[]"
      />
    </div>
    <div v-else class="py-4">
      <p class="text-gray-500 dark:text-gray-400 mb-4">{{ t("No task currently in focus") }}</p>
      <p v-if="suggestedTask" class="text-gray-700 dark:text-gray-300 mb-4">
        {{ t("Suggested task") }}: {{ suggestedTask.title }}
      </p>
      <Button
        v-if="suggestedTask"
        @click="emit('setCurrentFocus', suggestedTask)"
        class="p-button-outlined p-button-secondary w-full"
      >
        {{ t("Start this task") }}
      </Button>
    </div>
  </SectionWrapper>
</template>
