<template>
  <div class="mx-auto lg:ml-80">
    <section class="py-8">
      <div class="container px-4 mx-auto">
        <h1 class="mb-2 text-5xl font-bold font-heading">Edit task</h1>
        <form @submit.prevent="submitForm">
          <TaskForm v-if="task.id" />
          <div class="flex flex-wrap space-x-4">
            <div>
              <SubmitButton>
                <template v-slot:title>Save</template>
                <template v-slot:icon>
                  <device-floppy-icon />
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
  </div>
</template>

<script>
import TaskForm from "@/components/task/TaskForm.vue";
import { mapState } from "vuex";
import api from "@/api/api";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import DeviceFloppyIcon from "vue-tabler-icons/icons/DeviceFloppyIcon";

export default {
  name: "TaskEditPage",
  components: { DeviceFloppyIcon, SubmitButton, BackButton, TaskForm },
  data() {
    return {
      buttonTitle: "Save",
    };
  },
  computed: mapState({
    task: (state) => state.task.current,
  }),
  async created() {
    const task = await api.getTask(this.$route.params.id);
    this.$store.commit("task/setTask", task);
  },
  methods: {
    async submitForm() {
      await this.$store.dispatch("tasks/updateTask", this.task);
      this.$store.dispatch("task/clearTask");
      this.$router.push("/tasks");
    },
  },
};
</script>

<style scoped></style>
