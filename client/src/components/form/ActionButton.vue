<script setup lang="ts">
import { computed } from 'vue';

type ButtonType = 'primary' | 'secondary' | 'tertiary' | 'success' | 'warning' | 'danger';

interface Props {
  type?: ButtonType;
  icon?: string;
  label?: string;
  fullWidth?: boolean;
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'primary',
  fullWidth: false,
  disabled: false,
});

const buttonClass = computed(() => {
  const baseClasses = 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2';
  const widthClass = props.fullWidth ? 'w-full' : 'w-auto';

  const colorClasses = {
    primary: 'bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:active:bg-indigo-700',
    secondary: 'bg-gray-200 text-gray-700 hover:bg-gray-300 active:bg-gray-400 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 dark:active:bg-gray-500',
    tertiary: 'bg-transparent text-indigo-600 hover:bg-indigo-50 active:bg-indigo-100 focus:ring-indigo-500 border border-indigo-300 dark:text-indigo-300 dark:hover:bg-indigo-900 dark:active:bg-indigo-800 dark:border-indigo-600',
    success: 'bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600 dark:active:bg-green-700',
    warning: 'bg-yellow-500 text-white hover:bg-yellow-600 active:bg-yellow-700 focus:ring-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:active:bg-yellow-800',
    danger: 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600 dark:active:bg-red-700',
  };

  return `${baseClasses} ${widthClass} ${colorClasses[props.type]} ${props.disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
});
</script>

<template>
  <button
    :class="buttonClass"
    :disabled="disabled"
    @click="$emit('click')"
  >
    <i v-if="icon" :class="['pi', icon, 'mr-2']"></i>
    {{ label }}
    <slot></slot>
  </button>
</template>