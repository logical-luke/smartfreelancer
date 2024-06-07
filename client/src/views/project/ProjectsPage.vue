<template>
  <div class="container px-4">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Projects") }}</h3>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "ProjectsPage",
  computed: mapState({
    projects: (state) => state.projects.all,
  }),
};
</script>
