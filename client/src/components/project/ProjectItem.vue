<script setup lang="ts">
import {useI18n} from "vue-i18n";

const {t} = useI18n();
import {defineProps} from 'vue';
import SecondaryActionButton from "@/components/form/SecondaryActionButton.vue";
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import TaskStatusCard from "@/components/TaskStatusCard.vue";

const props = defineProps<{
  id: string;
  name: string;
  description: string | null;
  client: string;
  todoTasks: number;
  inProgressTasks: number;
  blockedTasks: number;
  completedTasks: number;
  dueDate: string;
}>();

const totalTasks = props.todoTasks + props.inProgressTasks + props.blockedTasks + props.completedTasks;
const progress = totalTasks > 0 ? (props.completedTasks / totalTasks) * 100 : 0;
</script>

<template>
  <div class="w-full bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
        <h3 class="text-xl sm:text-2xl font-bold text-white flex items-center">
          <i class="pi pi-folder-open mr-2"></i>
          <span class="truncate max-w-[200px] sm:max-w-[300px]">{{ name }}</span>
        </h3>
        <span class="text-sm font-medium px-3 py-1 bg-white bg-opacity-20 text-white rounded-full whitespace-nowrap">
          {{ client }}
        </span>
      </div>
    </div>

    <div class="p-4 sm:p-6">
      <p class="text-gray-700 mb-6">{{ description }}</p>

      <div class="mb-6">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm font-medium text-gray-600">{{ t("Progress") }}</span>
          <span class="text-sm font-bold text-indigo-600">{{ progress.toFixed(2) }}%</span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
          <div class="h-full bg-indigo-500 rounded-full transition-all duration-300 ease-in-out"
               :style="{ width: progress + '%' }"></div>
        </div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <TaskStatusCard :count="inProgressTasks" :label="t('In Progress')" icon="pi-spin pi-spinner" color="orange" />
        <TaskStatusCard :count="todoTasks" :label="t('Todo')" icon="pi-list" color="yellow" />
        <TaskStatusCard :count="blockedTasks" :label="t('Blocked')" icon="pi-ban" color="red" />
        <TaskStatusCard :count="completedTasks" :label="t('Completed')" icon="pi-check-circle" color="green" />
      </div>

      <div class="flex flex-col gap-4">
        <div class="flex items-center">
          <i class="pi pi-calendar mr-2 text-gray-500"></i>
          <span class="text-sm text-gray-600">{{ dueDate }}</span>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full">
          <SecondaryActionButton>
            <i class="pi pi-pencil mr-2"></i>{{ t("Edit") }}
          </SecondaryActionButton>
          <DestructiveActionButton>
            <i class="pi pi-trash mr-2"></i>{{ t("Delete") }}
          </DestructiveActionButton>
        </div>
      </div>
    </div>
  </div>
</template>



