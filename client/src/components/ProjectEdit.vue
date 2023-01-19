<template>
  <section class="py-8">
    <div class="container px-4 mx-auto"><h1 class="mb-2 text-5xl font-bold font-heading">Edit project</h1>
      <form @submit.prevent="submitForm">
        <ProjectForm />
        <div class="flex flex-wrap space-x-4">
          <button
            :disabled="this.project === null"
            class="inline-block w-full md:w-auto px-6 py-3 font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
            type="submit">Save
          </button>
          <BackButton />
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import ProjectForm from "@/components/ProjectForm.vue";
import { mapGetters, mapState } from "vuex";
import api from "@/api/api";
import store from "@/store";
import BackButton from "@/components/ui/BackButton.vue";

export default {
  name: "ProjectEdit",
  components: { BackButton, ProjectForm },
  computed: mapState({
    project: (state) => state.project.current
  }),
  async created() {
    const project = await api.getProject(this.$route.params.id);
    this.$store.commit("project/setProject", project);
  },
  methods: {
    async submitForm() {
      await api.updateProject(store.state.project.current);
      this.$store.commit("project/clearProject");
      this.$router.push("/projects");
    }
  }
};
</script>

<style scoped>

</style>
