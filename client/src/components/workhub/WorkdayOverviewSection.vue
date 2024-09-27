<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import ProgressBar from 'primevue/progressbar';

interface Props {
  workdayStartTime: number;
  workdayEndTime: number;
  timeLeftInWorkday: number;
  workdayProgress: number;
  isOverplanned: boolean;
}

const props = defineProps<Props>();
const { t } = useI18n();

const workdayStart = computed(() => new Date(props.workdayStartTime).toLocaleTimeString([], {
  hour: '2-digit',
  minute: '2-digit'
}));
const workdayEnd = computed(() => new Date(props.workdayEndTime).toLocaleTimeString([], {
  hour: '2-digit',
  minute: '2-digit'
}));

function formatTime(minutes: number): string {
  if (isNaN(minutes)) return '0h 0m';
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours}h ${mins}m`;
}
</script>

<template>
  <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">
      <i class="pi pi-clock mr-2"></i>{{ t("Workday Overview") }}
    </h3>
    <div class="flex flex-wrap">
      <div class="w-full px-2 mb-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t("Workday Time") }}</p>
            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ workdayStart }} - {{ workdayEnd }}</span>
          </div>
          <i class="pi pi-calendar text-xl text-blue-500"></i>
        </div>
        <div class="mt-2">
          <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
            <span :class="{ 'text-red-600': props.isOverplanned, 'text-blue-600': !props.isOverplanned }">
              <i class="pi pi-hourglass mr-1"></i>{{ formatTime(props.timeLeftInWorkday) }} {{ t("left") }}
            </span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
            <ProgressBar :value="props.workdayProgress" :showValue="false" class="h-2"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>