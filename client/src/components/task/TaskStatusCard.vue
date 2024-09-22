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
      bg: 'bg-orange-50',
      textMedium: 'text-orange-500',
      textSemiBold: 'text-orange-600',
      textBold: 'text-orange-700',
    },
    yellow: {
      bg: 'bg-yellow-50',
      textMedium: 'text-yellow-500',
      textSemiBold: 'text-yellow-600',
      textBold: 'text-yellow-700',
    },
    red: {
      bg: 'bg-red-50',
      textMedium: 'text-red-500',
      textSemiBold: 'text-red-500',
      textBold: 'text-red-700',
    },
    green: {
      bg: 'bg-green-50',
      textMedium: 'text-green-500',
      textSemiBold: 'text-green-600',
      textBold: 'text-green-700',
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
