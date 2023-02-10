<template>
  <new-button go-to="/project/create/">Project</new-button>
  <div class="flex flex-wrap">
    <transition-group name="fade" class="transition-element">
      <template v-for="project in projects" :key="project.id">
        <project-grid-item
          :id="project.id"
          :description="project.description"
          :name="project.name"
        ></project-grid-item>
      </template>
    </transition-group>
  </div>
</template>

<script>
import { mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ProjectGridItem from "@/components/project/ProjectGridItem.vue";

export default {
  name: "ProjectsPage",
  components: { ProjectGridItem, NewButton },
  computed: mapState({
    projects: (state) => state.projects.all
  }),
  mounted() {
    this.$store.dispatch("project/clearProject");
  }
};
</script>

<style scoped></style>
