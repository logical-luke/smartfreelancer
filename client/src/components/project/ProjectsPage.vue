<template>
  <div class="flex justify-between gap-4 flex-wrap items-center">
    <new-button go-to="/project/create/">Project</new-button>
    <search-controls @pattern-changed="setSearchPattern" />
    <pagination-controls @limit-change="setLimit" :page="currentPage" @page-change="setPage" :limit="limit" :total="filteredProjects.length" />
  </div>
  <div class="container px-4 mx-auto">
    <div class="bg-white rounded">
      <div class="pt-4 px-4 overflow-x-auto">
        <table class="table-auto w-full">
          <thead>
          <tr class="text-xs text-gray-500 text-left">
            <th class="font-medium">Name</th>
            <th class="font-medium">Action</th>
          </tr>
          </thead>
          <tbody>
          <transition-group name="fade" class="transition-element">
            <template v-for="(project, index) in paginatedProjects" :key="project.id">
              <project-grid-item :grey-background="index % 2 === 0" :id="project.id" :name="project.name" />
            </template>
          </transition-group>
          </tbody>
        </table>
      </div>
    </div>
  </div>


</template>

<script>
import { mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ProjectGridItem from "@/components/project/ProjectGridItem.vue";
import PaginationControls from "@/components/ui/PaginationControls.vue";
import SearchControls from "@/components/ui/SearchControls.vue";

export default {
  name: "ProjectsPage",
  components: { SearchControls, PaginationControls, ProjectGridItem, NewButton },
  data() {
    return {
      limit: 10,
      currentPage: 1,
      searchPattern: "",
    };
  },
  computed: mapState({
    projects: (state) => state.projects.all,
    filteredProjects() {
      return this.projects.filter((project) => project.name.toLowerCase().includes(this.searchPattern.toLowerCase()))
    },
    paginatedProjects() {
      const start = (this.currentPage - 1) * this.limit;
      const end = start + this.limit;

      return this.filteredProjects.slice(start, end);
    },
    totalProjects() {
      return this.paginatedProjects.length;
    },
  }),
  methods: {
    setLimit(limit) {
      this.limit = limit;
    },
    setPage(page) {
      this.currentPage = page;
    },
    setSearchPattern(searchPattern) {
      this.searchPattern = searchPattern;
      this.currentPage = 1;
    },
  },
  mounted() {
    this.$store.dispatch("project/clearProject");
  }
};
</script>

<style scoped></style>
