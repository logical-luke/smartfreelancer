<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import type { Task } from "@/interfaces/Task";
import CurrentFocusSection from "@/components/workhub/CurrentFocusSection.vue";
import TodayOverviewSection from "@/components/workhub/TodayOverviewSection.vue";
import PageHeader from "@/components/page/PageHeader.vue";

const { t } = useI18n();

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

const timeLeftForCurrentTask = ref(0);
const timerInterval = ref<number | null>(null);

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

function setCurrentFocus(task: Task) {
  currentFocus.value = task;
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
    <PageHeader icon="pi-bullseye" title="Deep Work Hub" />
    <div class="flex container flex-wrap gap-8 mb-8">
      <CurrentFocusSection
        :current-focus="currentFocus"
        :focus-notes="focusNotes"
        :is-tracking="isTracking"
        :time-left-for-current-task="timeLeftForCurrentTask"
        :suggested-task="suggestedTask"
        @update:task="updateTask"
        @toggle-time-tracking="toggleTimeTracking"
        @update:focus-notes="focusNotes = $event"
        @set-current-focus="setCurrentFocus"
      />

      <TodayOverviewSection
        :workday-start-time="workdayStartTime"
        :workday-end-time="workdayEndTime"
        :events-time="eventsTime"
        :daily-tasks="dailyTasks"
        :completed-tasks="completedTasks"
        v-model:daily-notes="dailyNotes"
        :workday-progress="workdayProgress"
        :time-left-in-workday="timeLeftInWorkday"
        :total-estimated-time="totalEstimatedTime"
        :is-overplanned="isOverplanned"
        @update:task="updateTask"
        @toggle-time-tracking="toggleTimeTracking"
      />
    </div>
  </div>
</template>