<template>
  <section class="py-8">
    <div class="container px-4 mx-auto">
      <h1 class="mb-2 text-5xl font-bold font-heading">Add task</h1>
      <form @submit.prevent="submitForm">
        <TaskForm />
        <div class="flex flex-wrap space-x-4">
          <div>
            <SubmitButton>
              <template v-slot:title>Add</template>
              <template v-slot:icon>
                <square-plus-icon />
              </template>
            </SubmitButton>
          </div>
          <div>
            <BackButton />
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import TaskForm from "@/components/task/TaskForm.vue";
import { mapState } from "vuex";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import SquarePlusIcon from "vue-tabler-icons/icons/SquarePlusIcon";

export default {
  name: "TaskCreate",
  components: { SquarePlusIcon, SubmitButton, BackButton, TaskForm },
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
