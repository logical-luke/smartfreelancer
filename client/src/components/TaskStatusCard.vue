<script setup lang="ts">
defineProps(['count', 'label', 'icon', 'color']);
</script>

<template>
  <div :class="`bg-${color}-50 rounded-lg p-3 flex flex-col items-center justify-center transition-all duration-300 hover:shadow-md`">
    <i :class="`pi ${icon} text-${color}-500 text-xl mb-2`"></i>
    <span :class="`text-2xl font-bold text-${color}-700`">{{ count }}</span>
    <p :class="`text-xs font-medium text-${color}-600 text-center`">{{ label }}</p>
  </div>
</template>
