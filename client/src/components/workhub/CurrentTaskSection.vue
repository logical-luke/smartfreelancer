<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import Button from 'primevue/button';
import type { Task } from "@/interfaces/Task";

const { t } = useI18n();

interface Props {
  currentFocus: Task | null;
  suggestedTask: Task | null;
}

const props = defineProps<Props>();

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
  <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-indigo-800 dark:text-indigo-200 mb-3">
      <i class="pi pi-play-circle mr-2"></i>{{ t("Current Task") }}
    </h3>
    <div v-if="currentFocus" class="space-y-4">
      <TaskItem
        :task="currentFocus"
        @update:task="updateTask"
        @toggleTimeTracking="toggleTimeTracking"
        class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow"
      />
    </div>
    <div v-else class="py-4">
      <p class="text-gray-500 dark:text-gray-400 mb-4">{{ t("No task currently in focus") }}</p>
      <p v-if="suggestedTask" class="text-gray-700 dark:text-gray-300 mb-4">
        {{ t("Suggested task") }}: {{ suggestedTask.title }}
      </p>
      <Button @click="emit('setCurrentFocus', suggestedTask)" v-if="suggestedTask"
              class="p-button-outlined p-button-secondary w-full">
        {{ t("Start this task") }}
      </Button>
    </div>
  </div>
</template>