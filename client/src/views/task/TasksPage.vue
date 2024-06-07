<template>
  <div class="container px-4">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Tasks") }}</h3>
    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "TasksPage",
  data() {
    return {
      limit: 10,
      currentPage: 1,
      searchPattern: "",
      bulkMode: false,
      selectedTasks: [],
    };
  },
  computed: mapState({
    tasks: (state) => state.tasks.all,
  }),
};
</script>

