<script setup lang="ts">
import { computed } from 'vue';

type ColorOption = 'red' | 'blue' | 'green' | 'yellow' | 'purple' | 'pink' | 'indigo' | 'teal' | 'orange' | 'cyan';

const colorClasses: Record<ColorOption, string> = {
  red: 'bg-gradient-to-r from-red-500 to-red-600 dark:from-red-600 dark:to-red-700',
  blue: 'bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700',
  green: 'bg-gradient-to-r from-green-500 to-green-600 dark:from-green-600 dark:to-green-700',
  yellow: 'bg-gradient-to-r from-yellow-500 to-yellow-600 dark:from-yellow-600 dark:to-yellow-700',
  purple: 'bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700',
  pink: 'bg-gradient-to-r from-pink-500 to-pink-600 dark:from-pink-600 dark:to-pink-700',
  indigo: 'bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700',
  teal: 'bg-gradient-to-r from-teal-500 to-teal-600 dark:from-teal-600 dark:to-teal-700',
  orange: 'bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700',
  cyan: 'bg-gradient-to-r from-cyan-500 to-cyan-600 dark:from-cyan-600 dark:to-cyan-700'
};

interface Props {
  title: string;
  icon: string;
  color: ColorOption;
}

const props = withDefaults(defineProps<Props>(), {
  color: 'blue'
});

const headerClass = computed(() => colorClasses[props.color]);
</script>

<template>
  <div class="rounded-xl shadow mb-6 overflow-hidden border border-gray-200 dark:border-gray-700">
    <div :class="[headerClass, 'p-3']">
      <h3 class="text-lg font-semibold text-white flex items-center">
        <i :class="[icon, 'mr-2']"></i>{{ title }}
      </h3>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4">
      <slot></slot>
    </div>
  </div>
</template>