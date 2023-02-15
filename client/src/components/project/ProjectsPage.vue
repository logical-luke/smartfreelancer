<template>
  <div class="flex justify-between gap-4 flex-wrap items-center">
    <div class="flex flex-wrap gap-2 flex-wrap">
      <new-button go-to="/project/create/">project</new-button>
      <bulk-edit-button @toggle-bulk="toggleBulk" />
      <transition name="fade" mode="out-in">
        <div v-if="bulkMode">
          <button
            type="button"
            @click="deleteSelected"
            class="inline-flex text-center items-center w-full px-3 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
          >
            <trash-icon size="20" />
            <span class="ml-2">
              Delete selected
            </span>
          </button>
        </div>
      </transition>
    </div>
    <search-controls @pattern-changed="setSearchPattern" />
    <pagination-controls @limit-change="setLimit"
                         :page="currentPage"
                         @page-change="setPage"
                         :limit="limit"
                         :total="filteredProjects.length" />
  </div>
  <div class="container px-4 mx-auto">
    <div class="bg-white rounded">
      <div class="pt-4 px-4 overflow-x-auto">
        <table class="table-auto w-full">
          <thead>
          <tr class="text-sm text-gray-500 text-left">
            <th v-if="bulkMode" class="font-medium w-8">
              <div class="flex items-center justify-center">
                <input @click="toggleAll" type="checkbox">
              </div>
            </th>
            <th class="font-medium">Name</th>
            <th class="font-medium">Action</th>
          </tr>
          </thead>
          <tbody>
          <transition-group name="fade-slower" class="transition-element">
            <template v-for="(project, index) in paginatedProjects" :key="project.id">
              <project-grid-item :bulkMode="bulkMode"
                                 @toggle-select="updateBulkItems"
                                 :selected="isSelected(project.id)"
                                 :grey-background="index % 2 === 0"
                                 :id="project.id"
                                 :name="project.name" />
            </template>
          </transition-group>
          </tbody>
        </table>
      </div>
    </div>
  </div>


</template>

<script>
import { mapActions, mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ProjectGridItem from "@/components/project/ProjectGridItem.vue";
import PaginationControls from "@/components/ui/PaginationControls.vue";
import SearchControls from "@/components/ui/SearchControls.vue";
import BulkEditButton from "@/components/ui/BulkEditButton.vue";
import TrashIcon from "vue-tabler-icons/icons/TrashIcon";
import { forEach } from "vue3-acies-treeselect";

export default {
  name: "ProjectsPage",
  components: { TrashIcon, BulkEditButton, SearchControls, PaginationControls, ProjectGridItem, NewButton },
  data() {
    return {
      limit: 10,
      currentPage: 1,
      searchPattern: "",
      bulkMode: false,
      selectedProjects: []
    };
  },
  computed: mapState({
    projects: (state) => state.projects.all,
    filteredProjects() {
      return this.projects.filter((project) => project.name.toLowerCase().includes(this.searchPattern.toLowerCase()));
    },
    paginatedProjects() {
      const start = (this.currentPage - 1) * this.limit;
      const end = start + this.limit;

      return this.filteredProjects.slice(start, end);
    }
  }),
  methods: {
    ...mapActions("projects", ["deleteProject"]),
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
    toggleBulk() {
      this.bulkMode = !this.bulkMode;
    },
    updateBulkItems(bulkSelectionAction) {
      if (bulkSelectionAction.action === "add") {
        this.selectedProjects.push(bulkSelectionAction.id);
      }

      if (bulkSelectionAction.action === "remove") {
        const index = this.selectedProjects.indexOf(bulkSelectionAction.id);
        if (index > -1) {
          this.selectedProjects.splice(index, 1);
        }
      }
    },
    deleteSelected() {
      if (this.selectedProjects.length === 0) {
        return;
      }
      this.selectedProjects.forEach((projectId) => {
        this.deleteProject(projectId);
      });
    },
    toggleAll() {
      if (this.selectedProjects.length < this.paginatedProjects.length) {
        this.selectedProjects = this.paginatedProjects.map((project) => project.id);
      } else {
        this.selectedProjects = [];
      }
    },
    isSelected(projectId) {
      return this.selectedProjects.includes(projectId);
    },
  },
  mounted() {
    this.$store.dispatch("project/clearProject");
  }
};
</script>

<style scoped></style>
