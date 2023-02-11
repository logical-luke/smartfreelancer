<template>
  <NewButton go-to="/task/create/">Task</NewButton>
  <div class="flex flex-wrap">
    <TransitionGroup name="fade" class="transition-element">
      <template v-for="(task, index) in tasks" :key="`task-${index}`">
        <TaskGridItem
          :id="task.id"
          :description="task.description"
          :name="task.name"
        ></TaskGridItem>
      </template>
    </TransitionGroup>
  </div>
</template>

<script>
import { mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import TaskGridItem from "@/components/task/TaskGridItem.vue";

export default {
  name: "TasksPage",
  components: { TaskGridItem, NewButton },
  computed: mapState({
    tasks: (state) => state.tasks.all,
  }),
  mounted() {
    this.$store.dispatch("task/clearTask");
  },
};
</script>

<style scoped></style>
