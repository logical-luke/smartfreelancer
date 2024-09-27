<script setup lang="ts">
import { defineProps, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const props = withDefaults(defineProps<{
  timeWorked: number;
  timeEstimated: number;
}>(), {
  timeWorked: 0,
  timeEstimated: 0
});

const { t } = useI18n();

const timeLeft = computed(() => Math.max(props.timeEstimated - props.timeWorked, 0));

const progressPercentage = computed(() => {
  if (props.timeEstimated === 0) return 0;
  return Math.min(Math.round((props.timeWorked / props.timeEstimated) * 100), 100);
});

const formatHours = (hours: number) => {
  if (hours < 1) return `${Math.round(hours * 60)}m`;
  return `${Math.floor(hours)}h ${Math.round((hours % 1) * 60)}m`;
};
</script>

<template>
  <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">
      <i class="pi pi-clock mr-2"></i>{{ t("Time Overview") }}
    </h3>
    <div class="flex flex-wrap -mx-2">
      <div class="w-full sm:w-1/3 px-2 mb-4 sm:mb-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t("Time Worked") }}</p>
            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ formatHours(timeWorked) }}</span>
          </div>
          <i class="pi pi-stopwatch text-xl text-green-500"></i>
        </div>
        <div class="mt-2">
          <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
            <div class="bg-green-600 h-1.5 rounded-full" :style="{ width: `${progressPercentage}%` }"></div>
          </div>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            {{ progressPercentage }}% {{ t("of estimated") }}
          </p>
        </div>
      </div>
      <div class="w-full sm:w-1/3 px-2 mb-4 sm:mb-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t("Time Estimated") }}</p>
            <span class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ formatHours(timeEstimated) }}</span>
          </div>
          <i class="pi pi-calendar text-xl text-yellow-500"></i>
        </div>
      </div>
      <div class="w-full sm:w-1/3 px-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t("Time Left") }}</p>
            <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ formatHours(timeLeft) }}</span>
          </div>
          <i class="pi pi-hourglass text-xl text-red-500"></i>
        </div>
      </div>
    </div>
  </div>
</template>