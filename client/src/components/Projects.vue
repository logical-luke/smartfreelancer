<template>
  <Sidebar />
  <div class="mx-auto lg:ml-80">
    <section class="py-8" v-for="project in projects" :key="project.id">
      <div class="container px-4 mx-auto">
        <div class="p-6 bg-white rounded shadow">
          <div class="mb-4">
            <h3 class="mb-2 font-medium">{{ project.name }}</h3>
            <p class="text-sm text-gray-500">
              Starting work and assigning tasks. Adding new features and test
              them to complete the project.
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import Sidebar from "@/components/Sidebar.vue";

let projects = {};

export default {
  name: "Projects",
  components: { Sidebar },
  computed: mapState({
    projects: (state) => state.projects.all,
    ...mapGetters(["getToken"])
  }),
  created() {
    this.$store.dispatch("projects/getAllProjects");
  }
};
</script>

<style scoped></style>
