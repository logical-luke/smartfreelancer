<script>
import { mapState } from "vuex";

export default {
  name: "ProjectsPage",
  computed: mapState({
    projects: (state) => state.projects.all,
  }),
};
</script>

<template>
  <div class="flex mb-8">
    <h3 class="text-xl font-bold">{{ $t("Projects") }}</h3>
  </div>
</template>
