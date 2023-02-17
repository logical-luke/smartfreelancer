<template>
  <div class="container px-4 mx-auto">
    <h1 class="mb-2 text-2xl font-bold font-heading">Add project</h1>
    <form @submit.prevent="submitForm">
      <ProjectForm />
      <div class="flex flex-wrap space-x-4">
        <div>
          <SubmitButton>
            Add
            <template #icon>
              <plus-icon size="20" />
            </template>
          </SubmitButton>
        </div>
        <div>
          <BackButton />
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import ProjectForm from "@/components/project/ProjectForm.vue";
import { mapState } from "vuex";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import PlusIcon from "vue-tabler-icons/icons/PlusIcon";

export default {
  name: "ProjectCreate",
  components: { PlusIcon, SubmitButton, BackButton, ProjectForm },
  computed: mapState({
    project: (state) => state.project.current,
    userId: (state) => state.user.id
  }),
  created() {
    this.$store.dispatch("project/clearProject");
  },
  methods: {
    async submitForm() {
      await this.$store.dispatch("projects/createProject", this.project);

      this.$router.push("/projects");
    }
  }
};
</script>

<style scoped></style>
