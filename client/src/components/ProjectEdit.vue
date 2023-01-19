<template>
  <section class="py-8">
    <div class="container px-4 mx-auto"><h1 class="mb-2 text-5xl font-bold font-heading">Edit project</h1>
      <ProjectForm />
    </div>
  </section>
</template>

<script>
import ProjectForm from "@/components/ProjectForm.vue";
import { mapGetters, mapState } from "vuex";

export default {
  name: "ProjectEdit",
  components: { ProjectForm },
  computed: mapState({
    project: (state) => state.project.current
  }),
  created() {
    this.$store.dispatch("project/getProject", this.$route.params.id);
  }
};
</script>

<style scoped>

</style>
