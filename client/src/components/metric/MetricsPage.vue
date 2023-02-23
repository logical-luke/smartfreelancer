<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Metrics") }}</h3>
    </div>
    <IncomeChart />
  </div>
</template>

<script>
import IncomeChart from "@/components/metric/IncomeChart.vue";

export default {
  name: "MetricsPage",
  components: { IncomeChart }
};
</script>

<style scoped></style>
