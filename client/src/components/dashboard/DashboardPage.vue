<template>
  <div class="mx-auto lg:ml-80">
    <div class="py-8 px-6">
      <div class="container px-4 mx-auto">
        <h2 class="text-2xl font-bold">
          Welcome<template v-if="name">, {{ name }}</template> 👋
        </h2>
      </div>
      <IncomeChart />
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import IncomeChart from "@/components/dashboard/IncomeChart.vue";

export default {
  name: "DashboardPage",
  components: { IncomeChart },
  computed: mapState({
    name: (state) => state.user.name,
  }),
};
</script>

<style scoped></style>
