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
    class="w-full inline-flex shadow p-4 justify-center flex-nowrap items-center font-medium text-white bg-red-500 hover:bg-red-700 rounded-md transition disabled:bg-slate-50 disabled:text-slate-500 duration-200"
  >
    <span>
      <slot></slot>
    </span>
  </button>
</template>
