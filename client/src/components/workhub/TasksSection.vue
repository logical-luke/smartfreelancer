<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import type { Task } from "@/interfaces/Task";

const { t } = useI18n();

interface Props {
  dailyTasks: Task[];
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:task', task: Task): void;
  (e: 'toggleTimeTracking', task: Task): void;
}>();

const hasDailyTasks = computed(() => props.dailyTasks.length > 0);

function updateTask(updatedTask: Task) {
  emit('update:task', updatedTask);
}

function toggleTimeTracking(task: Task) {
  emit('toggleTimeTracking', task);
}
</script>

<template>
  <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">
      <i class="pi pi-list mr-2"></i>{{ t("Tasks") }}
    </h3>
    <div v-if="hasDailyTasks" class="space-y-2">
      <TaskItem
        v-for="task in dailyTasks"
        :key="task.id"
        :task="task"
        @update:task="updateTask"
        @toggleTimeTracking="toggleTimeTracking"
        class="bg-white dark:bg-gray-700 p-2 rounded"
      />
    </div>
    <p v-else class="text-gray-500 dark:text-gray-400 py-2">
      {{ t("No tasks scheduled for today") }}
    </p>
  </div>
</template>