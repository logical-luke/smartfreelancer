<script setup lang="ts">
import { defineProps, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const props = withDefaults(defineProps<{
  inProgressTasks: number;
  todoTasks: number;
  blockedTasks: number;
  completedTasks: number;
}>(), {
  inProgressTasks: 0,
  todoTasks: 0,
  blockedTasks: 0,
  completedTasks: 0
});

const { t } = useI18n();

const items = [
  { count: () => props.inProgressTasks, label: 'In Progress', icon: 'pi-spin pi-spinner', color: 'orange' },
  { count: () => props.todoTasks, label: 'Todo', icon: 'pi-list', color: 'blue' },
  { count: () => props.blockedTasks, label: 'Blocked', icon: 'pi-ban', color: 'red' },
  { count: () => props.completedTasks, label: 'Completed', icon: 'pi-check-circle', color: 'green' }
];

const totalTasks = computed(() =>
  props.inProgressTasks + props.todoTasks + props.blockedTasks + props.completedTasks
);

const getPercentage = (count: number) => {
  return totalTasks.value > 0 ? Math.round((count / totalTasks.value) * 100) : 0;
};

const getColorClass = (color: string) => {
  const classes = {
    orange: 'from-orange-400 to-orange-600',
    blue: 'from-blue-400 to-blue-600',
    red: 'from-red-400 to-red-600',
    green: 'from-green-400 to-green-600'
  };
  return classes[color];
};
</script>

<template>
  <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-200 mb-3 flex items-center">
      <i class="pi pi-list mr-2"></i>{{ t("Task Overview") }}
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <div v-for="(item, index) in items" :key="index"
           class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
        <div :class="['h-1 bg-gradient-to-r', getColorClass(item.color)]"></div>
        <div class="p-3 flex items-center">
          <div :class="['w-12 h-12 rounded-full flex items-center justify-center bg-gradient-to-br', getColorClass(item.color)]">
            <i :class="['pi', item.icon, 'text-white text-xl']"></i>
          </div>
          <div class="ml-3 flex-grow">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t(item.label) }}</p>
            <div class="flex items-end justify-between">
              <span class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ item.count() }}
              </span>
              <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ getPercentage(item.count()) }}%
              </span>
            </div>
          </div>
        </div>
        <div class="bg-gray-100 dark:bg-gray-700 h-1 w-full">
          <div :class="['h-full bg-gradient-to-r', getColorClass(item.color)]" :style="{ width: `${getPercentage(item.count())}%` }"></div>
        </div>
      </div>
    </div>
  </div>
</template>