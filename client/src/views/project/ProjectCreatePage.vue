<template>
  <div class="container px-4 mx-auto">
    <h1 class="mb-2 text-2xl font-bold font-heading">Add project</h1>
    <form @submit.prevent="submitForm">
      <project-form />
      <div class="flex flex-wrap space-x-4">
        <div>
          <submit-button>
            Add
            <template #icon>
              <plus-icon size="20" />
            </template>
          </submit-button>
        </div>
        <div>
          <back-button />
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import ProjectForm from "@/components/project/ProjectForm.vue";
import { mapState } from "vuex";
import BackButton from "@/components/BackButton.vue";
import SubmitButton from "@/components/SubmitButton.vue";
import PlusIcon from "vue-tabler-icons/icons/PlusIcon";

export default {
  name: "ProjectCreate",
  components: { PlusIcon, SubmitButton, BackButton, ProjectForm },
  computed: mapState({
    project: (state) => state.project.current,
    userId: (state) => state.user.id,
  }),
  created() {
    this.$store.dispatch("project/clearProject");
  },
  methods: {
    async submitForm() {
      await this.$store.dispatch("projects/createProject", this.project);

      this.$router.push("/projects");
    },
  },
};
</script>

<style scoped></style>
