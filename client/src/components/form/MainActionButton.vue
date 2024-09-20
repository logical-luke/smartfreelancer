<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  type?: string;
}>();

const buttonType = props.type || 'button';
</script>

<template>
  <button
    :type="buttonType"
    class="inline-flex shadow p-4 min-w-40 justify-center flex-nowrap items-center font-medium text-white bg-indigo-500 hover:bg-indigo-700 rounded-md transition disabled:bg-slate-50 disabled:text-slate-500 duration-200"
  >
    <span>
      <slot></slot>
    </span>
  </button>
</template>
