<script setup lang="ts">
import { defineProps } from 'vue';

const props = defineProps<{
  goTo: string;
}>();

const goTo = props.goTo || '';
</script>

<template>
  <li>
    <RouterLink
      class="flex items-center pl-3 py-4 pr-4 hover:bg-gray-900 text-gray-50 rounded"
      :to="goTo"
      active-class="bg-indigo-500"
      exact
    >
      <span class="flex justify-center items-center mr-3">
        <slot name="icon"></slot>
      </span>
      <span><slot></slot></span>
    </RouterLink>
  </li>
</template>

