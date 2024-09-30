<script setup lang="ts">
import { ref } from 'vue';
import PageHeader from "@/components/page/PageHeader.vue";
import Chart from 'primevue/chart';

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
        label: function(context) {
          return context.dataset.label + ': ' + context.raw;
        }
      }
    }
  }
});
</script>

<template>
  <PageHeader title="Reports" icon="pi pi-chart-bar" />
  <div class="container mt-4">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">
        <i class="pi pi-users mr-2"></i>Client Overview
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4">Summary of client activities and engagements.</p>
      <Chart type="bar" :data="clientOverviewChartData" :options="chartOptions" class="w-full h-64" />
    </div>
    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">
        <i class="pi pi-folder mr-2"></i>Project Progress
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4">Current status and milestones of ongoing projects.</p>
      <Chart type="bar" :data="projectProgressChartData" :options="chartOptions" class="w-full h-64" />
    </div>
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-3">
        <i class="pi pi-check-circle mr-2"></i>Task Completion
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4">Overview of tasks completed and pending.</p>
      <Chart type="bar" :data="taskCompletionChartData" :options="chartOptions" class="w-full h-64" />
    </div>
    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-3">
        <i class="pi pi-dollar mr-2"></i>Expense Report
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4">Detailed breakdown of expenses incurred.</p>
      <Chart type="bar" :data="expenseReportChartData" :options="chartOptions" class="w-full h-64" />
    </div>
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-200 mb-3">
        <i class="pi pi-chart-line mr-2"></i>Income Analysis
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4">Analysis of income generated over the period.</p>
      <Chart type="bar" :data="incomeAnalysisChartData" :options="chartOptions" class="w-full h-64" />
    </div>
    <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900 dark:to-teal-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-teal-800 dark:text-teal-200 mb-3">
        <i class="pi pi-clock mr-2"></i>Time Tracking
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-4">Summary of time tracked for various activities.</p>
      <Chart type="bar" :data="timeTrackingChartData" :options="chartOptions" class="w-full h-64" />
    </div>
  </div>
</template>
