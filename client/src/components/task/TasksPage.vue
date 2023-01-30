<template>
  <div class="mx-auto lg:ml-80">
    <NewButton go-to="/task/create/">Task</NewButton>
    <transition name="fade">
      <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0">
        <template v-for="task in tasks" :key="task.id">
          <TaskPage
            :id="task.id"
            :description="task.description"
            :name="task.name"
          ></TaskPage>
        </template>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import TaskPage from "@/components/task/Task.vue";

export default {
  name: "TasksPage",
  components: { TaskPage, NewButton },
  computed: mapState({
    tasks: (state) => state.tasks.all,
  }),
  mounted() {
    this.$store.dispatch("task/clearTask");
  },
};
</script>

<style scoped></style>
