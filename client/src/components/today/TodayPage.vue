<template>
  <div class="container px-4 mx-auto">
    <div class="text-gray-600 font-medium flex flex-wrap mt-6 justify-center">
      {{ $t("There are no more scheduled tasks for today") }}.
      {{ $t("Enjoy your spare time") }} 🥳
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
