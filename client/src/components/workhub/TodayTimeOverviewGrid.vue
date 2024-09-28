<script setup lang="ts">
import { defineProps, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const props = withDefaults(defineProps<{
  totalEstimatedTime: number;
  eventsTime: number;
  timeLeftInWorkday: number;
  workdayProgress: number;
  isOverplanned: boolean;
}>(), {
  totalEstimatedTime: 0,
  eventsTime: 0,
  timeLeftInWorkday: 0,
  workdayProgress: 0,
  isOverplanned: false
});

const { t } = useI18n();

const formatTime = (minutes: number) => {
  if (isNaN(minutes)) return '0h 0m';
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours}h ${mins}m`;
};

const tasksProgressPercentage = computed(() => {
  if (props.timeLeftInWorkday === 0) return 0;
  return Math.min(Math.round((props.totalEstimatedTime / props.timeLeftInWorkday) * 100), 100);
});

const eventsProgressPercentage = computed(() => {
  if (props.timeLeftInWorkday === 0) return 0;
  return Math.min(Math.round((props.eventsTime / props.timeLeftInWorkday) * 100), 100);
});
</script>

<template>
  <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">
      <i class="pi pi-clock mr-2"></i>{{ t("Today's Time Overview") }}
    </h3>
    <div class="flex flex-wrap">
      <div class="w-full md:w-1/2 px-2 mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t("Remaining Tasks") }}</p>
            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ formatTime(props.totalEstimatedTime) }}</span>
          </div>
          <i class="pi pi-list text-xl text-green-500"></i>
        </div>
        <div class="mt-2">
          <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
            <div class="bg-green-600 h-1.5 rounded-full" :style="{ width: `${tasksProgressPercentage}%` }"></div>
          </div>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            {{ tasksProgressPercentage }}% {{ t("of workday left") }}
          </p>
        </div>
      </div>
      <div class="w-full md:w-1/2 px-2 mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t("Remaining Events") }}</p>
            <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ formatTime(props.eventsTime) }}</span>
          </div>
          <i class="pi pi-calendar-clock text-xl text-yellow-500"></i>
        </div>
        <div class="mt-2">
          <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
            <div class="bg-yellow-600 h-1.5 rounded-full" :style="{ width: `${eventsProgressPercentage}%` }"></div>
          </div>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            {{ eventsProgressPercentage }}% {{ t("of workday left") }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>