<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import ProgressBar from 'primevue/progressbar';
import Textarea from 'primevue/textarea';
import Card from 'primevue/card';
import type Task from "@/interfaces/task";
import TodayTimeOverviewGrid from "@/components/workhub/TodayTimeOverviewGrid.vue";
import WorkdayOverviewSection from "@/components/workhub/WorkdayOverviewSection.vue";

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

const hasDailyTasks = computed(() => props.dailyTasks.length > 0);
const hasCompletedTasks = computed(() => props.completedTasks.length > 0);

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

        <div>
          <h3 class="text-lg font-semibold mb-2 flex items-center text-gray-700 dark:text-gray-300">
            <i class="pi pi-list mr-2 text-indigo-500"></i>{{ t("Tasks") }}
          </h3>
          <div v-if="hasDailyTasks" class="space-y-2">
            <TaskItem
              v-for="task in dailyTasks"
              :key="task.id"
              :task="task"
              @update:task="updateTask"
              @toggleTimeTracking="toggleTimeTracking"
              class="bg-gray-50 dark:bg-gray-800 p-2 rounded"
            />
          </div>
          <p v-else class="text-gray-500 dark:text-gray-400 py-2">
            {{ t("No tasks scheduled for today") }}
          </p>
        </div>

        <div>
          <h3 class="text-lg font-semibold mb-2 flex items-center text-gray-700 dark:text-gray-300">
            <i class="pi pi-pencil mr-2 text-indigo-500"></i>{{ t("Daily Notes") }}
          </h3>
          <Textarea
            :value="dailyNotes"
            :placeholder="t('Add your daily notes here...')"
            :autoResize="true"
            rows="3"
            class="w-full bg-gray-50 dark:bg-gray-700"
            @input="$emit('update:dailyNotes', $event.target.value)"
          />
        </div>

        <div>
          <h3 class="text-lg font-semibold mb-2 flex items-center text-gray-700 dark:text-gray-300">
            <i class="pi pi-check-circle mr-2 text-green-500"></i>{{ t("Completed Tasks") }}
          </h3>
          <div v-if="hasCompletedTasks" class="space-y-2">
            <TaskItem
              v-for="task in completedTasks"
              :key="task.id"
              :task="task"
              @update:task="updateTask"
              class="bg-gray-50 dark:bg-gray-800 p-2 rounded"
            />
          </div>
          <p v-else class="text-gray-500 dark:text-gray-400 py-2">
            {{ t("No completed tasks yet") }}
          </p>
        </div>
      </div>
    </template>
  </Card>
</template>