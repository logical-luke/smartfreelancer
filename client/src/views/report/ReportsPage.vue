<script setup lang="ts">
import {ref} from 'vue';
import PageHeader from "@/components/page/PageHeader.vue";
import Chart from 'primevue/chart';
import SectionWrapper from "@/components/SectionWrapper.vue";

const clientOverviewChartData = ref({
  labels: ['Client A', 'Client B', 'Client C'],
  datasets: [
    {
      label: 'Client Activities',
      backgroundColor: '#42A5F5',
      data: [10, 20, 30]
    }
  ]
});

const projectProgressChartData = ref({
  labels: ['Project A', 'Project B', 'Project C'],
  datasets: [
    {
      label: 'Project Milestones',
      backgroundColor: '#66BB6A',
      data: [5, 15, 25]
    }
  ]
});

const taskCompletionChartData = ref({
  labels: ['Completed', 'Pending', 'In Progress'],
  datasets: [
    {
      label: 'Tasks',
      backgroundColor: '#FFA726',
      data: [50, 30, 20]
    }
  ]
});

const expenseReportChartData = ref({
  labels: ['Rent', 'Utilities', 'Salaries'],
  datasets: [
    {
      label: 'Expenses',
      backgroundColor: '#FF7043',
      data: [500, 300, 700]
    }
  ]
});

const incomeAnalysisChartData = ref({
  labels: ['January', 'February', 'March'],
  datasets: [
    {
      label: 'Income',
      backgroundColor: '#26C6DA',
      data: [1000, 1500, 2000]
    }
  ]
});

const timeTrackingChartData = ref({
  labels: ['Week 1', 'Week 2', 'Week 3'],
  datasets: [
    {
      label: 'Tracked Time',
      backgroundColor: '#AB47BC',
      data: [40, 35, 45]
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
        label: function (context) {
          return context.dataset.label + ': ' + context.raw;
        }
      }
    }
  }
});
</script>

<template>
  <PageHeader title="Reports" icon="pi pi-chart-bar"/>
  <div class="container mt-4">
    <SectionWrapper title="Client Overview" icon="pi pi-users" color="blue">
      <p class="text-gray-600 dark:text-gray-400 mb-4">Summary of client activities and engagements.</p>
      <Chart type="bar" :data="clientOverviewChartData" :options="chartOptions" class="w-full h-64"/>
    </SectionWrapper>

    <SectionWrapper title="Project Progress" icon="pi pi-folder" color="green">
      <p class="text-gray-600 dark:text-gray-400 mb-4">Current status and milestones of ongoing projects.</p>
      <Chart type="bar" :data="projectProgressChartData" :options="chartOptions" class="w-full h-64"/>
    </SectionWrapper>

    <SectionWrapper title="Task Completion" icon="pi pi-check-circle" color="yellow">
      <p class="text-gray-600 dark:text-gray-400 mb-4">Overview of tasks completed and pending.</p>
      <Chart type="bar" :data="taskCompletionChartData" :options="chartOptions" class="w-full h-64"/>
    </SectionWrapper>

    <SectionWrapper title="Expense Report" icon="pi pi-dollar" color="red">
      <p class="text-gray-600 dark:text-gray-400 mb-4">Detailed breakdown of expenses incurred.</p>
      <Chart type="bar" :data="expenseReportChartData" :options="chartOptions" class="w-full h-64"/>
    </SectionWrapper>

    <SectionWrapper title="Income Analysis" icon="pi pi-chart-line" color="purple">
      <p class="text-gray-600 dark:text-gray-400 mb-4">Analysis of income generated over the period.</p>
      <Chart type="bar" :data="incomeAnalysisChartData" :options="chartOptions" class="w-full h-64"/>
    </SectionWrapper>

    <SectionWrapper title="Time Tracking" icon="pi pi-clock" color="teal">
      <p class="text-gray-600 dark:text-gray-400 mb-4">Summary of time tracked for various activities.</p>
      <Chart type="bar" :data="timeTrackingChartData" :options="chartOptions" class="w-full h-64"/>
    </SectionWrapper>
  </div>
</template>