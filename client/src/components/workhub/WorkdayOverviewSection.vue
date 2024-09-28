<script setup lang="ts">
import {computed} from 'vue';
import {useI18n} from 'vue-i18n';
import ProgressBar from 'primevue/progressbar';
import type {DayOfWeek, WorkdaySettings} from "@/interfaces/Setting";

interface Props {
  workdaySettings: WorkdaySettings;
}

const props = defineProps<Props>();
const {t} = useI18n();

const currentDate = new Date();
const currentDay = currentDate.toLocaleDateString('en-US', {weekday: 'long'}).toLowerCase() as DayOfWeek;

// Use a type assertion here
const todayWorkday = computed(() => props.workdaySettings[currentDay as keyof WorkdaySettings]);

const workdayStart = computed(() => {
  if (!todayWorkday.value || !todayWorkday.value.start) return null;
  const [hours, minutes] = todayWorkday.value.start.split(':');
  const date = new Date(currentDate);
  date.setHours(parseInt(hours), parseInt(minutes), 0, 0);
  return date;
});

const workdayEnd = computed(() => {
  if (!todayWorkday.value || !todayWorkday.value.end) return null;
  const [hours, minutes] = todayWorkday.value.end.split(':');
  const date = new Date(currentDate);
  date.setHours(parseInt(hours), parseInt(minutes), 0, 0);
  return date;
});

const workdayStatus = computed(() => {
  if (!workdayStart.value || !workdayEnd.value) return 'no-workday';
  if (currentDate < workdayStart.value) return 'not-started';
  if (currentDate > workdayEnd.value) return 'finished';
  return 'ongoing';
});

const timeLeftInWorkday = computed(() => {
  if (workdayStatus.value !== 'ongoing') return 0;
  return Math.max(0, workdayEnd.value!.getTime() - currentDate.getTime());
});

const workdayProgress = computed(() => {
  if (workdayStatus.value === 'finished') return 100
  if (workdayStatus.value !== 'ongoing') return 0;
  const totalWorkdayTime = workdayEnd.value!.getTime() - workdayStart.value!.getTime();
  const elapsedTime = currentDate.getTime() - workdayStart.value!.getTime();
  return Math.min(100, (elapsedTime / totalWorkdayTime) * 100);
});

function formatTime(milliseconds: number): string {
  const hours = Math.floor(milliseconds / (1000 * 60 * 60));
  const minutes = Math.floor((milliseconds % (1000 * 60 * 60)) / (1000 * 60));
  return `${hours}h ${minutes}m`;
}

function formatTimeString(date: Date | null): string {
  return date ? date.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'}) : '--:--';
}
</script>

<template>
  <div
      class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">
      <i class="pi pi-clock mr-2"></i>{{ t("Workday Overview") }}
    </h3>
    <div class="flex flex-wrap">
      <div class="w-full px-2 mb-4">
        <div v-if="workdayStatus !== 'no-workday'"
             class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t("Workday Time") }}</p>
            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
              {{ formatTimeString(workdayStart) }} - {{ formatTimeString(workdayEnd) }}
            </span>
          </div>
          <i class="pi pi-calendar text-xl text-blue-500"></i>
        </div>
        <div v-else
             class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-center transition-all duration-300 hover:shadow-md">
          <i class="pi pi-sun text-3xl text-yellow-500 mr-3"></i>
          <span class="text-lg font-bold text-gray-700 dark:text-gray-300">
            {{ t("Today is a rest day. Enjoy your time off!") }}
          </span>
        </div>
        <div v-if="workdayStatus !== 'no-workday'" class="mt-2">
          <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
            <span v-if="workdayStatus === 'ongoing'" class="text-blue-600">
              <i class="pi pi-hourglass mr-1"></i>{{ formatTime(timeLeftInWorkday) }} {{ t("left") }}
            </span>
            <span v-else-if="workdayStatus === 'not-started'" class="text-yellow-600">
              {{ t("Workday hasn't started yet") }}
            </span>
            <span v-else-if="workdayStatus === 'finished'" class="text-green-600">
              {{ t("Workday has ended") }}
            </span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
            <ProgressBar :value="workdayProgress" :showValue="false" class="h-2"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>