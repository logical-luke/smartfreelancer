<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import Card from 'primevue/card';
import type { Task } from "@/interfaces/Task";
import CurrentTaskSection from "@/components/workhub/TrackedTaskSection.vue";
import TimeOverviewGrid from "@/components/report/TimeOverviewGrid.vue";
import TaskNoteSection from "@/components/workhub/TaskNoteSection.vue";

const { t } = useI18n();

interface Props {
  currentFocus: Task | null;
  focusNotes: string;
  isTracking: boolean;
  timeLeftForCurrentTask: number;
  suggestedTask: Task | null;
}

defineProps<Props>();

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

function updateFocusNotes(notes: string) {
  emit('update:focusNotes', notes);
}

function setCurrentFocus(task: Task) {
  emit('setCurrentFocus', task);
}
</script>

<template>
  <Card class="w-full bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200 dark:border-gray-700">
    <template #title>
      <div class="p-6 -mx-6 -mt-6 mb-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-2">
          <i class="pi pi-gauge text-2xl"></i>
          <span class="text-2xl font-semibold">{{ t("Current Focus") }}</span>
        </div>
      </div>
    </template>
    <template #content>
      <div class="space-y-6">
        <CurrentTaskSection
          :current-focus="currentFocus"
          :suggested-task="suggestedTask"
          @update:task="updateTask"
          @toggle-time-tracking="toggleTimeTracking"
          @set-current-focus="setCurrentFocus"
        />

        <TimeOverviewGrid
          v-if="currentFocus"
          :time-worked="currentFocus.trackedTime"
          :time-estimated="currentFocus.timeEstimate"
        />

        <TaskNoteSection
          v-if="currentFocus"
          :focus-notes="focusNotes"
          @update:focus-notes="updateFocusNotes"
        />
      </div>
    </template>
  </Card>
</template>