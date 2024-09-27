<script setup lang="ts">
type Color = 'orange' | 'yellow' | 'red' | 'green';

const props = defineProps<{
  count: number;
  label: string;
  icon: string;
  color: Color;
}>();

const color = props.color;

const getClass = (color: Color) => {
  const classes = {
    orange: {
      bg: 'bg-orange-50 dark:bg-orange-900',
      textMedium: 'text-orange-500 dark:text-orange-400',
      textSemiBold: 'text-orange-600 dark:text-orange-300',
      textBold: 'text-orange-700 dark:text-orange-200',
    },
    yellow: {
      bg: 'bg-yellow-50 dark:bg-yellow-900',
      textMedium: 'text-yellow-500 dark:text-yellow-400',
      textSemiBold: 'text-yellow-600 dark:text-yellow-300',
      textBold: 'text-yellow-700 dark:text-yellow-200',
    },
    red: {
      bg: 'bg-red-50 dark:bg-red-900',
      textMedium: 'text-red-500 dark:text-red-400',
      textSemiBold: 'text-red-500 dark:text-red-300',
      textBold: 'text-red-700 dark:text-red-200',
    },
    green: {
      bg: 'bg-green-50 dark:bg-green-900',
      textMedium: 'text-green-500 dark:text-green-400',
      textSemiBold: 'text-green-600 dark:text-green-300',
      textBold: 'text-green-700 dark:text-green-200',
    },
  };

  return classes[color];
};

const { bg, textMedium, textSemiBold, textBold } = getClass(color);

</script>

<template>
  <div :class="`${bg} rounded-lg p-3 flex flex-col items-center justify-center transition-all duration-300 hover:shadow`">
    <i :class="`pi ${icon} ${textMedium} text-xl mb-2`"></i>
    <span :class="`text-2xl font-bold ${textBold}`">{{ count }}</span>
    <p :class="`text-xs font-medium ${textSemiBold} text-center`">{{ label }}</p>
  </div>
</template>