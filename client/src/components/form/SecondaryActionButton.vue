<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  type?: string;
  fullWidth?: boolean;
}>();

const buttonType = props.type || 'button';
const fullWidth = props.fullWidth || false;
</script>

<template>
<button
  :type="buttonType"
  :class="{
    '': fullWidth,
    'md:min-w-40 md:w-fit': !fullWidth,
  }"
  class="w-full inline-flex shadow p-4 justify-center flex-nowrap items-center font-medium
         bg-white dark:bg-gray-800
         border-2 border-indigo-500 dark:border-indigo-400
         text-black dark:text-white
         hover:bg-indigo-500 dark:hover:bg-indigo-600
         hover:text-white
         rounded-md
         disabled:bg-slate-50 dark:disabled:bg-slate-700
         disabled:text-slate-500 dark:disabled:text-slate-400
         disabled:border-slate-200 dark:disabled:border-slate-600
         transition duration-200"
>
    <span>
      <slot></slot>
    </span>
  </button>
</template>
