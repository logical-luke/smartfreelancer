<template>
  <div class="flex justify-between gap-4 flex-wrap items-center">
    <div class="flex flex-wrap gap-2 flex-wrap">
      <new-button go-to="/task/create/">task</new-button>
      <bulk-edit-button :active="bulkMode" @toggle-bulk="toggleBulk" />
      <transition name="fade" mode="out-in">
        <div v-if="bulkMode">
          <button
            type="button"
            @click="deleteSelected"
            class="inline-flex text-center items-center w-full px-3 py-3 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded transition duration-200"
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
                         :total="filteredTasks.length" />
  </div>
  <div class="container px-4 mx-auto">
    <div class="bg-white rounded">
      <div class="pt-4 px-4 overflow-x-auto">
        <table class="table-auto w-full">
          <thead>
          <tr class="text-sm text-gray-500 text-left">
            <th v-if="bulkMode" class="font-medium w-8">
              <div class="flex items-center justify-center">
                <input :checked="allSelected" @click="toggleAll" type="checkbox">
              </div>
            </th>
            <th class="font-medium">Name</th>
            <th class="font-medium">Action</th>
          </tr>
          </thead>
          <tbody>
          <transition-group name="fade-slower" class="transition-element">
            <template v-for="(task, index) in paginatedTasks" :key="task.id">
              <task-list-item :bulkMode="bulkMode"
                                 @toggle-select="updateBulkItems"
                                 :selected="isSelected(task.id)"
                                 :grey-background="index % 2 === 0"
                                 :id="task.id"
                                 :name="task.name" />
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
import TaskListItem from "@/components/task/TaskListItem.vue";
import PaginationControls from "@/components/ui/PaginationControls.vue";
import SearchControls from "@/components/ui/SearchControls.vue";
import BulkEditButton from "@/components/ui/BulkEditButton.vue";
import TrashIcon from "vue-tabler-icons/icons/TrashIcon";
import { forEach } from "vue3-acies-treeselect";

export default {
  name: "TasksPage",
  components: { TrashIcon, BulkEditButton, SearchControls, PaginationControls, TaskListItem, NewButton },
  data() {
    return {
      limit: 10,
      currentPage: 1,
      searchPattern: "",
      bulkMode: false,
      selectedTasks: []
    };
  },
  computed: mapState({
    tasks: (state) => state.tasks.all,
    filteredTasks() {
      return this.tasks.filter((task) => task.name.toLowerCase().includes(this.searchPattern.toLowerCase()));
    },
    paginatedTasks() {
      const start = (this.currentPage - 1) * this.limit;
      const end = start + this.limit;

      return this.filteredTasks.slice(start, end);
    },
    allSelected() {
      return this.selectedTasks.length === this.paginatedTasks.length;
    }
  }),
  methods: {
    ...mapActions("tasks", ["deleteTask", "deleteTasks"]),
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
        this.selectedTasks.push(bulkSelectionAction.id);
      }

      if (bulkSelectionAction.action === "remove") {
        const index = this.selectedTasks.indexOf(bulkSelectionAction.id);
        if (index > -1) {
          this.selectedTasks.splice(index, 1);
        }
      }
    },
    deleteSelected() {
      if (this.selectedTasks.length === 0) {
        return;
      }
      this.$confirm.require({
        message: "Are you sure you want to delete " + this.selectedTasks.length + " tasks?",
        header: "Delete tasks",
        acceptClass: "confirm-button-accept",
        icon: "pi pi-exclamation-triangle",
        accept: () => {
          this.deleteTasks(this.selectedTasks);
          this.selectedTasks = [];
        }
      });
    },
    toggleAll() {
      if (this.selectedTasks.length < this.paginatedTasks.length) {
        this.selectedTasks = this.paginatedTasks.map((task) => task.id);
      } else {
        this.selectedTasks = [];
      }
    },
    isSelected(taskId) {
      return this.selectedTasks.includes(taskId);
    }
  },
  mounted() {
    this.$store.dispatch("task/clearTask");
  }
};
</script>

<style scoped></style>
