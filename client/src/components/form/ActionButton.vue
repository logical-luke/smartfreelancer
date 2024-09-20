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
    class="inline-flex shadow p-4 min-w-40 justify-center flex-nowrap items-center font-medium bg-white border-2 border-indigo-500 text-black hover:bg-indigo-500 hover:text-white rounded-md disabled:bg-slate-50 disabled:text-slate-500 transition duration-200"
  >
    <span>
      <slot></slot>
    </span>
  </button>
</template>
