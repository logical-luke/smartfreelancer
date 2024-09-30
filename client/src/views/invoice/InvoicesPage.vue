<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import SectionWrapper from "@/components/SectionWrapper.vue";
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
      backgroundColor: ['#66BB6A', '#FFA726', '#EF5350'],
      data: [10, 5, 2]
    }
  ]
});

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'right'
    },
    tooltip: {
      callbacks: {
        label: function(context) {
          return context.label + ': ' + context.raw;
        }
      }
    }
  }
});

const invoices = ref([
  { id: 1, client: 'TechCorp', amount: 2000, status: 'Paid', date: '2023-10-01', dueDate: '2023-10-15' },
  { id: 2, client: 'FinTech Inc', amount: 1500, status: 'Unpaid', date: '2023-10-05', dueDate: '2023-10-20' },
  { id: 3, client: 'HealthPlus', amount: 500, status: 'Overdue', date: '2023-09-25', dueDate: '2023-10-10' },
  { id: 4, client: 'EduSmart', amount: 1000, status: 'Paid', date: '2023-10-08', dueDate: '2023-10-23' },
  { id: 5, client: 'GreenEnergy', amount: 3000, status: 'Unpaid', date: '2023-10-12', dueDate: '2023-10-27' }
]);

const totalInvoices = computed(() => invoices.value.length);
const totalAmount = computed(() => invoices.value.reduce((sum, invoice) => sum + invoice.amount, 0));
const paidInvoices = computed(() => invoices.value.filter(invoice => invoice.status === 'Paid').length);
const unpaidInvoices = computed(() => invoices.value.filter(invoice => invoice.status === 'Unpaid').length);
const overdueInvoices = computed(() => invoices.value.filter(invoice => invoice.status === 'Overdue').length);

function addDraftInvoice() {
  // Implement add invoice logic here
  console.log('Adding new invoice');
}
</script>

<template>
  <PageHeader title="Invoices" icon="pi pi-file-invoice"/>
  <div class="container mt-4">
    <SectionWrapper title="Invoice Summary" icon="pi pi-chart-pie" color="green">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p class="mb-2"><i class="pi pi-file mr-2"></i>Total Invoices: {{ totalInvoices }}</p>
          <p class="mb-2"><i class="pi pi-dollar mr-2"></i>Total Amount: ${{ totalAmount }}</p>
          <p class="mb-2"><i class="pi pi-check-circle mr-2"></i>Paid Invoices: {{ paidInvoices }}</p>
          <p class="mb-2"><i class="pi pi-exclamation-circle mr-2"></i>Unpaid Invoices: {{ unpaidInvoices }}</p>
          <p class="mb-2"><i class="pi pi-times-circle mr-2"></i>Overdue Invoices: {{ overdueInvoices }}</p>
        </div>
        <div>
          <Chart type="pie" :data="invoiceSummaryChartData" :options="chartOptions" class="h-64"/>
        </div>
      </div>
    </SectionWrapper>

    <SectionWrapper title="Invoice List" icon="pi pi-list" color="blue">
      <DataTable :value="invoices" class="p-datatable-sm" responsiveLayout="scroll" :paginator="true" :rows="5">
        <Column field="id" header="ID" sortable />
        <Column field="client" header="Client" sortable />
        <Column field="amount" header="Amount" sortable>
          <template #body="slotProps">
            ${{ slotProps.data.amount }}
          </template>
        </Column>
        <Column field="status" header="Status" sortable />
        <Column field="date" header="Invoice Date" sortable />
        <Column field="dueDate" header="Due Date" sortable />
      </DataTable>
    </SectionWrapper>

    <AddItemFloatingButton @click="addDraftInvoice" />
  </div>
</template>