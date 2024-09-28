<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import type { Task } from "@/interfaces/Task";

const { t } = useI18n();

interface Props {
  completedTasks: Task[];
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:task', task: Task): void;
}>();

const hasCompletedTasks = computed(() => props.completedTasks.length > 0);

function updateTask(updatedTask: Task) {
  emit('update:task', updatedTask);
}
</script>

<template>
  <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">
      <i class="pi pi-check-circle mr-2"></i>{{ t("Completed Tasks") }}
    </h3>
    <div v-if="hasCompletedTasks" class="space-y-2">
      <TaskItem
        v-for="task in completedTasks"
        :key="task.id"
        :task="task"
        @update:task="updateTask"
        class="bg-white dark:bg-gray-700 p-2 rounded"
      />
    </div>
    <p v-else class="text-gray-500 dark:text-gray-400 py-2">
      {{ t("No completed tasks yet") }}
    </p>
  </div>
</template>