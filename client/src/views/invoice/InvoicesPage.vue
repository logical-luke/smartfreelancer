<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import Chart from 'primevue/chart';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import { computed, ref } from "vue";

const invoiceSummaryChartData = ref({
  labels: ['Paid', 'Unpaid', 'Overdue'],
  datasets: [
    {
      label: 'Invoices',
      backgroundColor: ['#66BB6A', '#FFA726', '#FF7043'],
      data: [10, 5, 2]
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

const invoices = ref([
  { id: 1, client: 'TechCorp', amount: 2000, status: 'Paid', date: '2023-10-01' },
  { id: 2, client: 'FinTech Inc', amount: 1500, status: 'Unpaid', date: '2023-10-05' },
  { id: 3, client: 'HealthPlus', amount: 500, status: 'Overdue', date: '2023-09-25' }
]);

const totalInvoices = computed(() => invoices.value.length);
const totalAmount = computed(() => invoices.value.reduce((sum, invoice) => sum + invoice.amount, 0));
const paidInvoices = computed(() => invoices.value.filter(invoice => invoice.status === 'Paid').length);
const unpaidInvoices = computed(() => invoices.value.filter(invoice => invoice.status === 'Unpaid').length);
const overdueInvoices = computed(() => invoices.value.filter(invoice => invoice.status === 'Overdue').length);
</script>

<template>
  <PageHeader title="Invoices" icon="pi pi-receipt"/>
  <div class="container mt-4">
    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">
        <i class="pi pi-list mr-2"></i>Invoice List
      </h3>
      <DataTable :value="invoices" class="w-full">
        <Column field="id" header="ID"/>
        <Column field="client" header="Client"/>
        <Column field="amount" header="Amount"/>
        <Column field="status" header="Status"/>
        <Column field="date" header="Date"/>
      </DataTable>
    </div>
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
        <i class="pi pi-chart-bar mr-2"></i>Invoice Summary
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4"><i class="pi pi-file mr-2"></i>Total Invoices: {{ totalInvoices }}</p>
      <p class="text-gray-600 dark:text-gray-400 mb-4"><i class="pi pi-dollar mr-2"></i>Total Amount: ${{ totalAmount }}</p>
      <p class="text-gray-600 dark:text-gray-400 mb-4"><i class="pi pi-check-circle mr-2"></i>Paid Invoices: {{ paidInvoices }}</p>
      <p class="text-gray-600 dark:text-gray-400 mb-4"><i class="pi pi-exclamation-circle mr-2"></i>Unpaid Invoices: {{ unpaidInvoices }}</p>
      <p class="text-gray-600 dark:text-gray-400 mb-4"><i class="pi pi-times-circle mr-2"></i>Overdue Invoices: {{ overdueInvoices }}</p>
    </div>
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">
        <i class="pi pi-chart-line mr-2"></i>Invoice Summary Chart
      </h3>
      <Chart type="bar" :data="invoiceSummaryChartData" :options="chartOptions" class="w-full h-64"/>
    </div>
    <AddItemFloatingButton @click="addDraftInvoice" />
  </div>
</template>

<style scoped>
.container {
  @apply mt-4;
}
</style>