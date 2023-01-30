<template>
  <div class="mx-auto lg:ml-80">
    <div class="py-8 px-6">
      <IncomeChart />
    </div>
  </div>
</template>

<script>
import IncomeChart from "@/components/report/IncomeChart.vue";

export default {
  name: "ReportsPage",
  components: { IncomeChart }
};
</script>

<style scoped></style>
