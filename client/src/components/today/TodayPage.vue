<template>
  <div class="container px-4 mx-auto">
    <h2 class="text-2xl font-bold">
      Welcome
      <template v-if="name">, {{ name }}</template>
      👋
    </h2>
    <div class="text-gray-600 font-medium flex flex-wrap mt-6 justify-center">
      There are no more scheduled tasks for today. You can relax 🥳
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "TodayPage",
  computed: mapState({
    name: (state) => state.user.name,
  }),
};
</script>

<style scoped></style>
