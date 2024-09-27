<script setup lang="ts">
import {computed} from 'vue';
import {useI18n} from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';
import Card from 'primevue/card';
import type Task from "@/interfaces/task";
import TimeOverviewGrid from "@/components/report/TimeOverviewGrid.vue";

const {t} = useI18n();

interface Props {
  currentFocus: Task | null;
  focusNotes: string;
  isTracking: boolean;
  timeLeftForCurrentTask: number;
  suggestedTask: Task | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:task', task: Task): void;
  (e: 'toggleTimeTracking', task: Task): void;
  (e: 'update:focusNotes', notes: string): void;
  (e: 'setCurrentFocus', task: Task): void;
}>();

function updateTask(updatedTask: Task) {
  emit('update:task', updatedTask);
}

function toggleTimeTracking(task: Task) {
  emit('toggleTimeTracking', task);
}

function formatTime(minutes: number): string {
  if (isNaN(minutes)) return '0h 0m';
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours}h ${mins}m`;
}
</script>

<template>
  <Card class="w-full bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow">
    <template #title>
      <div
          class="bg-gradient-to-r from-indigo-400 to-indigo-500 dark:from-indigo-600 dark:to-indigo-700 p-6 -mx-6 -mt-6 mb-6">
        <div class="flex items-center space-x-2 text-white">
          <i class="pi pi-bullseye text-2xl"></i>
          <span class="text-2xl font-semibold">{{ t("Current Focus") }}</span>
        </div>
      </div>
    </template>
    <template #content>
      <div v-if="currentFocus" class="space-y-4">
        <TaskItem
            :task="currentFocus"
            @update:task="updateTask"
            @toggleTimeTracking="toggleTimeTracking"
            class="bg-indigo-50 dark:bg-indigo-900 p-4 rounded-lg shadow"
        />
        <TimeOverviewGrid
          :time-worked="currentFocus.trackedTime"
          :time-estimated="currentFocus.timeEstimate"
        />
        <div>
          <h3 class="text-lg font-semibold mb-2 flex items-center text-gray-700 dark:text-gray-300">
            <i class="pi pi-pencil mr-2 text-indigo-500"></i>{{ t("Task Notes") }}
          </h3>
          <Textarea
              :value="focusNotes"
              :placeholder="t('Add your task notes here...')"
              :autoResize="true"
              rows="3"
              class="w-full bg-gray-50 dark:bg-gray-700"
              @input="emit('update:focusNotes', $event.target.value)"
          />
        </div>
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
    </template>
  </Card>
</template>