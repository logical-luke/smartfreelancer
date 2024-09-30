<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import Chart from 'primevue/chart';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import { computed, ref } from "vue";

const expenseSummaryChartData = ref({
  labels: ['Rent', 'Utilities', 'Salaries'],
  datasets: [
    {
      label: 'Expenses',
      backgroundColor: ['#FF7043', '#66BB6A', '#42A5F5'],
      data: [500, 300, 700]
    }
  ]
});

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top'
    },
    tooltip: {
      callbacks: {
        label: function(context) {
          return context.dataset.label + ': ' + context.raw;
        }
      }
    }
  }
});

const expenses = ref([
  { id: 1, category: 'Rent', amount: 500, date: '2023-10-01' },
  { id: 2, category: 'Utilities', amount: 300, date: '2023-10-05' },
  { id: 3, category: 'Salaries', amount: 700, date: '2023-10-10' }
]);

const totalExpenses = computed(() => expenses.value.length);
const totalAmount = computed(() => expenses.value.reduce((sum, expense) => sum + expense.amount, 0));
</script>

<template>
  <PageHeader title="Expenses" icon="pi-wallet"/>
  <div class="container mt-4">
    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-3">
        <i class="pi pi-dollar mr-2"></i>Expense Summary
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4"><i class="pi pi-file mr-2"></i>Total Expenses: {{ totalExpenses }}</p>
      <p class="text-gray-600 dark:text-gray-400 mb-4"><i class="pi pi-dollar mr-2"></i>Total Amount: ${{ totalAmount }}</p>
    </div>
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">
        <i class="pi pi-chart-bar mr-2"></i>Expense Chart
      </h3>
      <Chart type="bar" :data="expenseSummaryChartData" :options="chartOptions" class="w-full h-64"/>
    </div>
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
        <i class="pi pi-list mr-2"></i>Expense List
      </h3>
      <DataTable :value="expenses" class="p-datatable-gridlines">
        <Column field="id" header="ID" />
        <Column field="category" header="Category" />
        <Column field="amount" header="Amount" />
        <Column field="date" header="Date" />
      </DataTable>
    </div>
    <AddItemFloatingButton @click="addDraftExpense" />
  </div>
</template>

<style scoped>
.container {
  @apply mt-4;
}
</style>