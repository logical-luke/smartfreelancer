<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import Card from 'primevue/card';
import type { Task }  from "@/interfaces/Task";
import TodayTimeOverviewGrid from "@/components/workhub/TodayTimeOverviewGrid.vue";
import WorkdayOverviewSection from "@/components/workhub/WorkdayOverviewSection.vue";
import TasksSection from "@/components/workhub/TasksSection.vue";
import DailyNotesSection from "@/components/workhub/DailyNotesSection.vue";
import CompletedTasksSection from "@/components/workhub/CompletedTasksSection.vue";

const { t } = useI18n();

interface Props {
  workdayStartTime: number;
  workdayEndTime: number;
  eventsTime: number;
  dailyTasks: Task[];
  completedTasks: Task[];
  dailyNotes: string;
  workdayProgress: number;
  timeLeftInWorkday: number;
  totalEstimatedTime: number;
  isOverplanned: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:task', task: Task): void;
  (e: 'toggleTimeTracking', task: Task): void;
  (e: 'update:dailyNotes', notes: string): void;
}>();

const workdayStart = computed(() => new Date(props.workdayStartTime).toLocaleTimeString([], {
  hour: '2-digit',
  minute: '2-digit'
}));
const workdayEnd = computed(() => new Date(props.workdayEndTime).toLocaleTimeString([], {
  hour: '2-digit',
  minute: '2-digit'
}));

function updateTask(updatedTask: Task) {
  emit('update:task', updatedTask);
}

function toggleTimeTracking(task: Task) {
  emit('toggleTimeTracking', task);
}

function updateDailyNotes(notes: string) {
  emit('update:dailyNotes', notes);
}
</script>

<template>
  <Card class="w-full bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
    <template #title>
      <div class="bg-gradient-to-r from-indigo-400 to-indigo-500 dark:from-indigo-600 dark:to-indigo-700 p-6 -mx-6 -mt-6 mb-6">
        <div class="flex items-center space-x-2 text-white">
          <i class="pi pi-calendar text-2xl"></i>
          <span class="text-2xl font-semibold">{{ t("Today's Overview") }}</span>
        </div>
      </div>
    </template>
    <template #content>
      <div class="space-y-6">
        <WorkdayOverviewSection :workday-settings="{
          monday: { start: '09:00', end: '17:00' },
          tuesday: { start: '09:00', end: '17:00' },
          wednesday: { start: '09:00', end: '17:00' },
          thursday: { start: '09:00', end: '17:00' },
          friday: { start: '09:00', end: '16:00' },
          saturday: { start: '06:00', end: '07:00' },
          sunday: { start: '', end: '' }
        }" />

        <TodayTimeOverviewGrid
          :total-estimated-time="props.totalEstimatedTime"
          :events-time="props.eventsTime"
          :tasks-progress-percentage="props.workdayProgress"
          :events-progress-percentage="props.workdayProgress"
          :time-left-in-workday="props.timeLeftInWorkday"
          :workday-progress="props.workdayProgress"
          :is-overplanned="props.isOverplanned"
        />

        <TasksSection
          :daily-tasks="props.dailyTasks"
          @update:task="updateTask"
          @toggleTimeTracking="toggleTimeTracking"
        />

        <DailyNotesSection
          :daily-notes="props.dailyNotes"
          @update:dailyNotes="updateDailyNotes"
        />

        <CompletedTasksSection
          :completed-tasks="props.completedTasks"
          @update:task="updateTask"
        />
      </div>
    </template>
  </Card>
</template>