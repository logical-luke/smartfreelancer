<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Metrics") }}</h3>
    </div>
    <div class="flex flex-wrap">
      <div class="flex-grow">
        <revenue-chart />
      </div>
      <div class="flex-grow">
        <income-chart />
      </div>
      <div class="flex-grow">
        <expenses-chart />
      </div>
    </div>
  </div>
</template>

<script>
import IncomeChart from "@/components/metric/IncomeChart.vue";
import RevenueChart from "@/components/metric/RevenueChart.vue";
import ExpensesChart from "@/components/metric/ExpensesChart.vue";

export default {
  name: "MetricsPage",
  components: { ExpensesChart, RevenueChart, IncomeChart },
};
</script>

<style scoped></style>
