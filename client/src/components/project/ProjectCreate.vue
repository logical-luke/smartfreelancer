<template>
  <div class="mx-auto lg:ml-80">
    <section class="py-8">
      <div class="container px-4 mx-auto"><h1 class="mb-2 text-5xl font-bold font-heading">Add project</h1>
        <form @submit.prevent="submitForm">
          <ProjectForm />
          <div class="flex flex-wrap space-x-4">
            <SubmitButton :title="buttonTitle" />
            <BackButton />
          </div>
        </form>
      </div>
    </section>
  </div>
</template>

<script>
import ProjectForm from "@/components/project/ProjectForm.vue";
import { mapState } from "vuex";
import api from "@/api/api";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";

export default {
  name: "ProjectCreate",
  components: { SubmitButton, BackButton, ProjectForm },
  data() {
    return {
      buttonTitle: 'Add'
    }
  },
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
