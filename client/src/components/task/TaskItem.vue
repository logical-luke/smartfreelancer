<script setup lang="ts">
import {computed, ref} from 'vue';
import Checkbox from 'primevue/checkbox';
import Tag from 'primevue/tag';
import Button from 'primevue/button';

interface Task {
  id: number;
  title: string;
  completed: boolean;
  dueDate?: string;
  scheduledDate?: string;
  project?: string;
  client: string;
  timeEstimate?: number;
  trackedTime?: number;
  status: 'Todo' | 'In Progress' | 'Blocked' | 'Completed';
  estimatedRevenue?: number;
  subtasks?: Task[];
}

const props = defineProps<{
  task: Task;
}>();

const emit = defineEmits<{
  (e: 'update:task', task: Task): void;
  (e: 'toggleTimeTracking', task: Task): void;
}>();

const isTracking = ref(false);

const isOverdue = computed(() => {
  if (!props.task.dueDate || props.task.completed) return false;
  return new Date(props.task.dueDate) < new Date();
});

function toggleComplete() {
  emit('update:task', { ...props.task, completed: !props.task.completed });
}

function formatDate(date: string): string {
  return new Date(date).toLocaleDateString();
}

function formatTime(minutes: number): string {
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours}h ${mins}m`;
}

function formatCurrency(amount: number): string {
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
}

function toggleTimeTracking() {
  isTracking.value = !isTracking.value;
  emit('toggleTimeTracking', props.task);
}
</script>

<template>
  <div :class="[
    'task-item rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 mb-4 p-4 sm:p-6',
    { 'border-l-4': true },
    { 'border-yellow-300 bg-white': task.status === 'Todo' },
    { 'border-orange-300 bg-white': task.status === 'In Progress' },
    { 'border-green-300 bg-gray-100': task.status === 'Completed' },
    { 'border-red-300 bg-white': task.status === 'Blocked' },
  ]">
    <div class="flex">
      <Checkbox v-model="task.completed" :binary="true" class="mt-1 mr-3 flex-shrink-0" @change="toggleComplete" />
      <div class="flex-grow">
        <div class="flex flex-wrap items-start mb-3">
          <h3 :class="['text-lg font-semibold mr-2 break-words', { 'line-through': task.completed }]">
            {{ task.title }}
          </h3>
          <span :class="[
            'text-xs font-medium px-2 py-1 rounded flex items-center',
            { 'bg-yellow-100 text-yellow-800': task.status === 'Todo' },
            { 'bg-orange-100 text-orange-800': task.status === 'In Progress' },
            { 'bg-green-100 text-green-800': task.status === 'Completed' },
            { 'bg-red-100 text-red-800': task.status === 'Blocked' },
          ]">
            <i :class="[
              'mr-1',
              { 'pi pi-list': task.status === 'Todo' },
              { 'pi pi-spin pi-spinner': task.status === 'In Progress' },
              { 'pi pi-check': task.status === 'Completed' },
              { 'pi pi-ban': task.status === 'Blocked' },
            ]"></i>
            {{ task.status }}
          </span>
        </div>
        <div class="text-sm text-gray-600 flex flex-wrap gap-3 items-center">
          <span v-if="task.project" class="flex items-center" v-tooltip.top="'Project'">
            <i class="pi pi-folder mr-1"></i>{{ task.project }}
          </span>
          <span v-if="task.client" class="flex items-center" v-tooltip.top="'Client'">
            <i class="pi pi-user mr-1"></i>{{ task.client }}
          </span>
          <span v-if="task.dueDate" :class="{ 'text-red-500': isOverdue }" class="flex items-center">
            <i class="pi pi-calendar-times mr-1"></i>Due: {{ formatDate(task.dueDate) }}
          </span>
          <span v-if="task.scheduledDate" class="flex items-center">
            <i class="pi pi-calendar mr-1"></i>Scheduled: {{ formatDate(task.scheduledDate) }}
          </span>
          <span v-if="task.timeEstimate" class="flex items-center">
            <i class="pi pi-clock mr-1"></i>Est: {{ formatTime(task.timeEstimate) }}
          </span>
          <span v-if="task.trackedTime" class="flex items-center">
            <i class="pi pi-stopwatch mr-1"></i>Tracked: {{ formatTime(task.trackedTime) }}
          </span>
          <span v-if="task.estimatedRevenue" class="flex items-center">
            <i class="pi pi-dollar mr-1"></i>Est. Revenue: {{ formatCurrency(task.estimatedRevenue) }}
          </span>
          <button
              :class="[
              'text-xs px-2 py-1 rounded transition-colors duration-200 flex items-center',
              isTracking
                ? 'bg-red-100 text-red-800 hover:bg-red-200'
                : 'bg-green-100 text-green-800 hover:bg-green-200'
            ]"
              @click="toggleTimeTracking"
          >
            <i :class="['mr-1', isTracking ? 'pi pi-pause' : 'pi pi-play']"></i>
            {{ isTracking ? 'Stop' : 'Start' }} Tracking
          </button>
        </div>
      </div>
    </div>
    <div v-if="task.subtasks && task.subtasks.length > 0" class="pl-4 sm:pl-8 pr-4 pb-4 mt-4 border-t border-gray-100">
      <h4 class="text-sm font-semibold text-gray-500 mb-2 mt-2">Subtasks:</h4>
      <TaskItem
          v-for="subtask in task.subtasks"
          :key="subtask.id"
          :task="subtask"
          class="mt-2"
      />
    </div>
  </div>
</template>





