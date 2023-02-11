<template>
  <div class="container px-4 mx-auto">
    <h2 class="text-2xl font-bold">
      Welcome
      <template v-if="name">, {{ name }}</template>
      👋
    </h2>
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
