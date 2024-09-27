<script setup lang="ts">
const handleClick = (event: MouseEvent) => {
  (event.target as HTMLElement).blur();
};
</script>

<template>
  <button
    @click="handleClick"
    class="fixed bottom-6 right-6 w-16 h-16 bg-indigo-500 dark:bg-indigo-600 text-white rounded-full shadow hover:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300 flex items-center justify-center"
  >
    <i class="pi pi-plus text-2xl"></i>
  </button>
</template>