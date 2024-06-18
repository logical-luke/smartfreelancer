<template>
  <div class="flex mb-8">
    <h3 class="text-xl font-bold">{{ $t("Tasks") }}</h3>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "TasksPage",
  computed: mapState({
    tasks: (state) => state.tasks.all,
  }),
};
</script>

