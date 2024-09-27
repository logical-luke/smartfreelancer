<script setup lang="ts">
import {ref, computed, onMounted, onUnmounted} from 'vue';
import {useI18n} from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import Button from 'primevue/button';
import ProgressBar from 'primevue/progressbar';
import Textarea from 'primevue/textarea';
import Card from 'primevue/card';
import type Task from "@/interfaces/task";

const {t} = useI18n();

// Mock data - TODO: Replace with actual data fetching
const currentFocus = ref<Task | null>({
  id: 1,
  title: 'Design new landing page',
  completed: false,
  dueDate: '2023-06-15',
  project: 'Website Redesign',
  client: 'TechCorp',
  clientId: 'tech-corp-id',
  projectId: 'website-redesign-id',
  timeEstimate: 480,
  trackedTime: 300,
  status: 'In Progress',
  estimatedRevenue: 2000,
});

const focusNotes = ref('These are some notes for the current focus task.');
const dailyNotes = ref('These are some general notes for the day.');

// Mock data - TODO: Replace with actual data fetching
const dailyTasks = ref<Task[]>([
  {
    id: 2,
    title: 'Implement user authentication',
    completed: false,
    dueDate: '2023-06-20',
    scheduledDate: '2023-06-20',
    project: 'Mobile App',
    client: 'FinTech Inc',
    clientId: 'fintech-inc-id',
    projectId: 'mobile-app-id',
    timeEstimate: 360,
    trackedTime: 0,
    status: 'Todo',
    estimatedRevenue: 1500,
  },
  {
    id: 3,
    title: 'Write API documentation',
    completed: false,
    dueDate: '2023-06-21',
    project: 'Backend Services',
    client: 'TechCorp',
    clientId: 'tech-corp-id',
    projectId: 'backend-services-id',
    timeEstimate: 240,
    trackedTime: 0,
    status: 'Todo',
    estimatedRevenue: 1000,
  },
]);

// Mock data - TODO: Replace with actual data fetching
const completedTasks = ref<Task[]>([
  {
    id: 4,
    title: 'Create wireframes',
    completed: true,
    project: 'Website Redesign',
    client: 'TechCorp',
    clientId: 'tech-corp-id',
    projectId: 'website-redesign-id',
    timeEstimate: 120,
    trackedTime: 90,
    status: 'Completed',
    estimatedRevenue: 500,
  },
]);

const isTracking = ref(false);
const workdayStartTime = ref(new Date().setHours(9, 0, 0, 0)); // 9:00 AM
const workdayEndTime = ref(new Date().setHours(17, 0, 0, 0)); // 5:00 PM
const eventsTime = ref(120); // Mock data: 2 hours of events

// Computed properties
const hasDailyTasks = computed(() => dailyTasks.value.length > 0);
const hasCompletedTasks = computed(() => completedTasks.value.length > 0);

const totalEstimatedTime = computed(() => {
  return dailyTasks.value.reduce((total, task) => total + (task.timeEstimate || 0), 0);
});

const timeLeftInWorkday = computed(() => {
  const now = new Date();
  return Math.max(0, workdayEndTime.value - now.getTime()) / (1000 * 60); // in minutes
});

const isOverplanned = computed(() => totalEstimatedTime.value > timeLeftInWorkday.value);

const workdayProgress = computed(() => {
  const totalWorkdayTime = workdayEndTime.value - workdayStartTime.value;
  const elapsedTime = Date.now() - workdayStartTime.value;
  return Math.min(100, Math.max(0, (elapsedTime / totalWorkdayTime) * 100));
});

const suggestedTask = computed(() => {
  return dailyTasks.value.find(task => task.status === 'Todo') || null;
});

const workdayStart = computed(() => new Date(workdayStartTime.value).toLocaleTimeString([], {
  hour: '2-digit',
  minute: '2-digit'
}));
const workdayEnd = computed(() => new Date(workdayEndTime.value).toLocaleTimeString([], {
  hour: '2-digit',
  minute: '2-digit'
}));

const timeLeftForCurrentTask = ref(0);
const timerInterval = ref(null);

function updateTimeLeft() {
  if (currentFocus.value && currentFocus.value.timeEstimate) {
    timeLeftForCurrentTask.value = Math.max(0, currentFocus.value.timeEstimate - currentFocus.value.trackedTime);
  } else {
    timeLeftForCurrentTask.value = 0;
  }
}

function startTimer() {
  updateTimeLeft();
  timerInterval.value = setInterval(updateTimeLeft, 60000); // Update every minute
}

function stopTimer() {
  if (timerInterval.value) {
    clearInterval(timerInterval.value);
  }
}

onMounted(() => {
  startTimer();
});

onUnmounted(() => {
  stopTimer();
});

// Functions
function updateTask(updatedTask: Task) {
  // TODO: Replace with actual implementation
  console.log('Updating task:', updatedTask);
}

function toggleTimeTracking(task: Task) {
  isTracking.value = !isTracking.value;
  if (isTracking.value) {
    startTracking();
  } else {
    stopTracking();
  }
}

function startTracking() {
  // TODO: Replace with actual implementation
  console.log('Starting time tracking');
}

function stopTracking() {
  // TODO: Replace with actual implementation
  console.log('Stopping time tracking');
}

function formatTime(minutes: number): string {
  if (isNaN(minutes)) return '0h 0m';
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours}h ${mins}m`;
}
</script>

<template>
  <div>
    <div class="flex mb-8">
      <div>
        <h3 class="text-2xl font-bold">{{ t("Deep Work Hub") }}</h3>
      </div>
    </div>
    <div class="space-y-6">
      <!-- Current Focus Section -->
      <Card class="shadow hover:shadow-md transition-shadow duration-300">
        <template #title>
          <div class="flex items-center mb-4 space-x-2 text-black dark:text-white">
            <i class="pi pi-bolt text-2xl"></i>
            <span class="text-2xl font-semibold">{{ t("Current Focus") }}</span>
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
            <div class="grid grid-cols-3 gap-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
              <div class="text-center">
                <i class="pi pi-clock text-2xl text-indigo-500 mb-2"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400 block">{{ t("Tracked") }}</span>
                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                  {{ formatTime(currentFocus.trackedTime) }}</p>
              </div>
              <div class="text-center">
                <i class="pi pi-calendar text-2xl text-green-500 mb-2"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400 block">{{ t("Estimated") }}</span>
                <p class="text-xl font-bold text-green-600 dark:text-green-400">{{
                    formatTime(currentFocus.timeEstimate)
                  }}</p>
              </div>
              <div class="text-center">
                <i class="pi pi-hourglass text-2xl text-yellow-500 mb-2"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400 block">{{ t("Time Left") }}</span>
                <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{
                    formatTime(timeLeftForCurrentTask)
                  }}</p>
              </div>
            </div>
            <div>
              <h3 class="text-lg font-semibold mb-2 flex items-center text-gray-700 dark:text-gray-300">
                <i class="pi pi-pencil mr-2 text-indigo-500"></i>{{ t("Task Notes") }}
              </h3>
              <Textarea
                  v-model="focusNotes"
                  :placeholder="t('Add your task notes here...')"
                  :autoResize="true"
                  rows="3"
                  class="w-full bg-gray-50 dark:bg-gray-700"
              />
            </div>
          </div>
          <div v-else class="py-4">
            <p class="text-gray-500 dark:text-gray-400 mb-4">{{ t("No task currently in focus") }}</p>
            <p v-if="suggestedTask" class="text-gray-700 dark:text-gray-300 mb-4">
              {{ t("Suggested task") }}: {{ suggestedTask.title }}
            </p>
            <Button @click="currentFocus = suggestedTask" v-if="suggestedTask"
                    class="p-button-outlined p-button-secondary w-full">
              {{ t("Start this task") }}
            </Button>
          </div>
        </template>
      </Card>

      <!-- Today's Overview Section -->
      <Card class="shadow hover:shadow-md transition-shadow duration-300">
        <template #title>
          <div class="flex items-center space-x-2 text-indigo-600 dark:text-indigo-400">
            <i class="pi pi-calendar text-2xl"></i>
            <span class="text-2xl font-semibold">{{ t("Today's Overview") }}</span>
          </div>
        </template>
        <template #content>
          <div class="space-y-6">
            <div>
              <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                <span>{{ workdayStart }} - {{ workdayEnd }}</span>
                <span :class="{ 'text-red-600': isOverplanned, 'text-green-600': !isOverplanned }">
                  {{ formatTime(timeLeftInWorkday) }} {{ t("left") }}
                </span>
              </div>
              <ProgressBar :value="workdayProgress" :showValue="false" class="h-2"/>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <div class="bg-indigo-50 dark:bg-indigo-900 p-3 rounded-lg text-center">
                <i class="pi pi-list text-2xl text-indigo-500 mb-2"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400 block">{{ t("Tasks Time") }}</span>
                <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{
                    formatTime(totalEstimatedTime)
                  }}</p>
              </div>
              <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg text-center">
                <i class="pi pi-calendar-times text-2xl text-blue-500 mb-2"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400 block">{{ t("Events Time") }}</span>
                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ formatTime(eventsTime) }}</p>
              </div>
              <div class="bg-green-50 dark:bg-green-900 p-3 rounded-lg text-center">
                <i class="pi pi-clock text-2xl text-green-500 mb-2"></i>
                <span class="text-sm text-gray-500 dark:text-gray-400 block">{{ t("Workday Left") }}</span>
                <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ formatTime(timeLeftInWorkday) }}</p>
              </div>
            </div>

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
                  v-model="dailyNotes"
                  :placeholder="t('Add your daily notes here...')"
                  :autoResize="true"
                  rows="3"
                  class="w-full bg-gray-50 dark:bg-gray-700"
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
    </div>
  </div>
</template>