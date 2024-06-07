<template>
  <section class="py-8">
    <div class="container px-4 mx-auto">
      <h1 class="mb-2 text-2xl font-bold font-heading">Edit task</h1>
      <form @submit.prevent="submitForm">
        <task-form />
        <div class="flex flex-wrap space-x-4">
          <div>
            <submit-button>
              Save
              <template #icon>
                <device-floppy-icon />
              </template>
            </submit-button>
          </div>
          <div>
            <back-button />
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import TaskForm from "@/components/task/TaskForm.vue";
import { mapState } from "vuex";
import BackButton from "@/components/BackButton.vue";
import SubmitButton from "@/components/SubmitButton.vue";
import DeviceFloppyIcon from "vue-tabler-icons/icons/DeviceFloppyIcon";
import store from "@/store";
import router from "@/router";
import { useRoute } from "vue-router";

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
    const task = await store.getters["tasks/getTaskById"](this.route.params.id);
    store.commit("task/setTask", task);
  },
  methods: {
    async submitForm() {
      await store.dispatch("tasks/updateTask", this.task);
      await router.push("/tasks");
    },
  },
  setup() {
    const route = useRoute();

    return { route };
  },
};
</script>

<style scoped></style>
