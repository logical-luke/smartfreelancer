<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import type { Task } from "@/interfaces/Task";
import SectionWrapper from "@/components/SectionWrapper.vue";

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
  <SectionWrapper :title="t('Tasks')" icon="pi pi-list" color="indigo">
    <div v-if="hasDailyTasks" class="space-y-2">
      <TaskItem
        v-for="task in dailyTasks"
        :key="task.id"
        :task="task"
        @update:task="updateTask"
        @toggleTimeTracking="toggleTimeTracking"
      />
    </div>
    <p v-else class="text-gray-500 dark:text-gray-400 py-2">
      {{ t("No tasks scheduled for today") }}
    </p>
  </SectionWrapper>
</template>