<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import SectionWrapper from "@/components/SectionWrapper.vue";
import Chart from 'primevue/chart';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import { computed, ref } from "vue";

const expenseSummaryChartData = ref({
  labels: ['Rent', 'Utilities', 'Salaries', 'Equipment', 'Marketing'],
  datasets: [
    {
      label: 'Expenses',
      backgroundColor: ['#FF7043', '#66BB6A', '#42A5F5', '#FFCA28', '#EC407A'],
      data: [500, 300, 700, 200, 150]
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
          return context.label + ': $' + context.raw;
        }
      }
    }
  }
});

const expenses = ref([
  { id: 1, category: 'Rent', amount: 500, date: '2023-10-01', description: 'Office rent' },
  { id: 2, category: 'Utilities', amount: 300, date: '2023-10-05', description: 'Electricity and water' },
  { id: 3, category: 'Salaries', amount: 700, date: '2023-10-10', description: 'Employee salaries' },
  { id: 4, category: 'Equipment', amount: 200, date: '2023-10-15', description: 'New computers' },
  { id: 5, category: 'Marketing', amount: 150, date: '2023-10-20', description: 'Online ads' }
]);

const totalExpenses = computed(() => expenses.value.length);
const totalAmount = computed(() => expenses.value.reduce((sum, expense) => sum + expense.amount, 0));

function addDraftExpense() {
  // Implement add expense logic here
  console.log('Adding new expense');
}
</script>

<template>
  <PageHeader title="Expenses" icon="pi pi-wallet"/>
  <div class="container mt-4">
    <SectionWrapper title="Expense Summary" icon="pi pi-dollar" color="red">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p class="text-xl mb-2"><i class="pi pi-file mr-2"></i>Total Expenses: {{ totalExpenses }}</p>
          <p class="text-2xl font-bold"><i class="pi pi-dollar mr-2"></i>Total Amount: ${{ totalAmount }}</p>
        </div>
        <div>
          <Chart type="doughnut" :data="expenseSummaryChartData" :options="chartOptions" class="h-64"/>
        </div>
      </div>
    </SectionWrapper>

    <SectionWrapper title="Expense List" icon="pi pi-list" color="blue">
      <DataTable :value="expenses" class="p-datatable-sm" responsiveLayout="scroll" :paginator="true" :rows="5">
        <Column field="id" header="ID" sortable />
        <Column field="category" header="Category" sortable />
        <Column field="amount" header="Amount" sortable>
          <template #body="slotProps">
            ${{ slotProps.data.amount }}
          </template>
        </Column>
        <Column field="date" header="Date" sortable />
        <Column field="description" header="Description" />
      </DataTable>
    </SectionWrapper>

    <AddItemFloatingButton @click="addDraftExpense" />
  </div>
</template>
