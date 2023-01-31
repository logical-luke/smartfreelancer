<template>
  <div class="mx-auto lg:ml-80">
    <new-button go-to="/project/create/">Project</new-button>
    <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0">
      <transition-group name="fade" class="transition-element">
        <template v-for="project in projects" :key="project.id">
          <project-page
            :id="project.id"
            :description="project.description"
            :name="project.name"
          ></project-page>
        </template>
      </transition-group>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ProjectPage from "@/components/project/Project.vue";

export default {
  name: "ProjectsPage",
  components: { ProjectPage, NewButton },
  computed: mapState({
    projects: (state) => state.projects.all,
  }),
  mounted() {
    this.$store.dispatch("project/clearProject");
  },
};
</script>

<style scoped></style>
