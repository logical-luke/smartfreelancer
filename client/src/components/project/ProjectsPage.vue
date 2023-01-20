<template>
  <div class="mx-auto lg:ml-80">
    <NewButton go-to="/project/create/">Project</NewButton>
    <transition name="fade">
      <div>
        <template v-for="project in projects" :key="project.id">
          <Project :id="project.id" :description="project.description" :name="project.name"></Project>
        </template>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import Project from "@/components/project/Project.vue";

let projects = {};

export default {
  name: "Projects",
  components: { Project, NewButton },
  computed: mapState({
    projects: (state) => state.projects.all
  }),
  created() {
    this.$store.dispatch("projects/fetchAllProjects");
  },
  mounted() {
    this.$store.commit("project/clearProject");
  }
};
</script>

<style scoped></style>
