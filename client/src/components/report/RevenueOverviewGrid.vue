<script setup lang="ts">
import { defineProps, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import SectionWrapper from "@/components/SectionWrapper.vue";

const props = withDefaults(defineProps<{
  income?: number;
  expenses?: number;
  revenue?: number;
  invoiced?: number;
  paid?: number;
  estimated?: number;
}>(), {
  income: 0,
  expenses: 0,
  revenue: 0,
  invoiced: 0,
  paid: 0,
  estimated: 0
});

const { t } = useI18n();

const formatCurrency = (value: number) => {
  const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    notation: 'compact',
    maximumFractionDigits: 1
  });
  return formatter.format(value);
};

const items = computed(() => [
  { label: "Income", value: props.income, icon: "pi-money-bill", color: "blue" },
  { label: "Expenses", value: props.expenses, icon: "pi-wallet", color: "red" },
  { label: "Revenue", value: props.revenue, icon: "pi-chart-bar", color: "green" },
  { label: "Invoiced", value: props.invoiced, icon: "pi-file-pdf", color: "yellow" },
  { label: "Paid", value: props.paid, icon: "pi-check-square", color: "purple" },
  { label: "Estimated", value: props.estimated, icon: "pi-chart-line", color: "orange" }
]);

const getColorClass = (color: string, type: 'bg' | 'text', intensity: number) => {
  const classes = {
    blue: {
      bg: ['bg-blue-100', 'bg-blue-200', 'bg-blue-300'],
      text: ['text-blue-500', 'text-blue-600', 'text-blue-700']
    },
    red: {
      bg: ['bg-red-100', 'bg-red-200', 'bg-red-300'],
      text: ['text-red-500', 'text-red-600', 'text-red-700']
    },
    green: {
      bg: ['bg-green-100', 'bg-green-200', 'bg-green-300'],
      text: ['text-green-500', 'text-green-600', 'text-green-700']
    },
    yellow: {
      bg: ['bg-yellow-100', 'bg-yellow-200', 'bg-yellow-300'],
      text: ['text-yellow-500', 'text-yellow-600', 'text-yellow-700']
    },
    purple: {
      bg: ['bg-purple-100', 'bg-purple-200', 'bg-purple-300'],
      text: ['text-purple-500', 'text-purple-600', 'text-purple-700']
    },
    orange: {
      bg: ['bg-orange-100', 'bg-orange-200', 'bg-orange-300'],
      text: ['text-orange-500', 'text-orange-600', 'text-orange-700']
    }
  };
  return classes[color][type][intensity - 1];
};
</script>

<template>
  <SectionWrapper :title="t('Revenue Overview')" icon="pi pi-dollar" color="purple">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
      <div v-for="item in items" :key="item.label"
           class="bg-white dark:bg-gray-800 rounded-lg p-3 flex items-center justify-between shadow hover:shadow-md transition-all duration-300">
        <div class="flex items-center">
          <div :class="[getColorClass(item.color, 'bg', 1), 'w-10 h-10 rounded-full flex items-center justify-center']">
            <i :class="['pi', item.icon, getColorClass(item.color, 'text', 2), 'text-lg']"></i>
          </div>
          <div class="ml-3">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ t(item.label) }}</p>
            <span :class="['text-sm font-bold', getColorClass(item.color, 'text', 2)]">
              {{ formatCurrency(item.value) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </SectionWrapper>
</template>