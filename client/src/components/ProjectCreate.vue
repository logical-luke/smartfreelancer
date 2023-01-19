<template>
  <section class="py-8">
    <div class="container px-4 mx-auto"><h1 class="mb-2 text-5xl font-bold font-heading">Add project</h1>
      <form @submit.prevent="submitForm">
        <ProjectForm />
        <div class="flex flex-wrap space-x-4">
          <button
            class="inline-block w-full md:w-auto px-6 py-3 font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
            type="submit">Add
          </button>
          <BackButton />
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import ProjectForm from "@/components/ProjectForm.vue";
import { mapState } from "vuex";
import api from "@/api/api";
import BackButton from "@/components/ui/BackButton.vue";

export default {
  name: "ProjectCreate",
  components: { BackButton, ProjectForm },
  computed: mapState({
    project: (state) => state.project.current,
    userId: (state) => state.user.id
  }),
  created() {
    this.$store.commit("project/setProject", {
      name: null,
      description: null
    });
  },
  methods: {
    async submitForm() {
      await api.createProject(this.project);

      this.$router.push("/projects");
    }
  }
};
</script>

<style scoped>

</style>
