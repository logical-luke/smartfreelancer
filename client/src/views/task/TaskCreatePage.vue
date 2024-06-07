<template>
  <h1 class="mb-2 text-2xl font-bold font-heading">Add task</h1>
  <form @submit.prevent="submitForm">
    <TaskForm/>
    <div class="flex flex-wrap space-x-4">
      <div>
        <submit-button>
          Add
          <template #icon>
            <square-plus-icon/>
          </template>
        </submit-button>
      </div>
      <div>
        <back-button/>
      </div>
    </div>
  </form>
</template>

<script>
import TaskForm from "@/components/task/TaskForm.vue";
import {mapState} from "vuex";
import BackButton from "@/components/BackButton.vue";
import SubmitButton from "@/components/SubmitButton.vue";
import SquarePlusIcon from "vue-tabler-icons/icons/SquarePlusIcon";

export default {
  name: "TaskCreate",
  components: {SquarePlusIcon, SubmitButton, BackButton, TaskForm},
  computed: mapState({
    task: (state) => state.task.current,
    userId: (state) => state.user.id,
  }),
  created() {
    this.$store.dispatch("task/clearTask");
  },
  methods: {
    async submitForm() {
      await this.$store.dispatch("tasks/createTask", this.task);

      this.$router.push("/tasks");
    },
  },
};
</script>

<style scoped></style>
