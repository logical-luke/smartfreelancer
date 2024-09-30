<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import TaskItem from "@/components/task/TaskItem.vue";
import type { Task } from "@/interfaces/Task";
import SectionWrapper from "@/components/SectionWrapper.vue";

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
  <SectionWrapper :title="t('Completed Tasks')" icon="pi pi-check-circle" color="green">
    <div v-if="hasCompletedTasks" class="space-y-2">
      <TaskItem
        v-for="task in completedTasks"
        :key="task.id"
        :task="task"
        @update:task="updateTask"
      />
    </div>
    <p v-else class="text-gray-500 dark:text-gray-400 py-2">
      {{ t("No completed tasks yet") }}
    </p>
  </SectionWrapper>
</template>