<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
    <div class="flex justify-between gap-4 flex-wrap items-center">
      <div class="flex flex-wrap gap-2 flex-wrap">
        <new-button go-to="/client/create/">client</new-button>
        <bulk-edit-button :active="bulkMode" @toggle-bulk="toggleBulk" />
        <transition name="fade" mode="out-in">
          <div v-if="bulkMode">
            <button
              type="button"
              @click="deleteSelected"
              class="inline-flex text-center items-center w-full px-3 py-3 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded transition duration-200"
            >
              <trash-icon size="20" />
              <span class="ml-2"> Delete selected </span>
            </button>
          </div>
        </transition>
      </div>
      <search-controls @pattern-changed="setSearchPattern" />
      <pagination-controls
        @limit-change="setLimit"
        :page="currentPage"
        @page-change="setPage"
        :limit="limit"
        :total="filteredClients.length"
      />
    </div>
    <div class="container px-4 mx-auto">
      <div class="bg-white rounded">
        <div class="pt-4 px-4 overflow-x-auto">
          <table class="table-auto w-full">
            <thead>
              <tr class="text-sm text-gray-500 text-left">
                <th v-if="bulkMode" class="font-medium w-8">
                  <div class="flex items-center justify-center">
                    <input
                      :checked="allSelected"
                      @click="toggleAll"
                      type="checkbox"
                    />
                  </div>
                </th>
                <th class="font-medium">Name</th>
                <th class="font-medium">Action</th>
              </tr>
            </thead>
            <tbody>
              <transition-group name="fade-slower" class="transition-element">
                <template
                  v-for="(client, index) in paginatedClients"
                  :key="client.id"
                >
                  <client-list-item
                    :bulkMode="bulkMode"
                    @toggle-select="updateBulkItems"
                    :selected="isSelected(client.id)"
                    :grey-background="index % 2 === 0"
                    :id="client.id"
                    :name="client.name"
                  />
                </template>
              </transition-group>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ClientListItem from "@/components/client/ClientListItem.vue";
import PaginationControls from "@/components/ui/PaginationControls.vue";
import SearchControls from "@/components/ui/SearchControls.vue";
import BulkEditButton from "@/components/ui/BulkEditButton.vue";
import TrashIcon from "vue-tabler-icons/icons/TrashIcon";

export default {
  name: "ClientsPage",
  components: {
    TrashIcon,
    BulkEditButton,
    SearchControls,
    PaginationControls,
    ClientListItem,
    NewButton,
  },
  data() {
    return {
      limit: 10,
      currentPage: 1,
      searchPattern: "",
      bulkMode: false,
      selectedClients: [],
    };
  },
  computed: mapState({
    clients: (state) => state.clients.all,
    filteredClients() {
      return this.clients.filter((client) =>
        client.name.toLowerCase().includes(this.searchPattern.toLowerCase())
      );
    },
    paginatedClients() {
      const start = (this.currentPage - 1) * this.limit;
      const end = start + this.limit;

      return this.filteredClients.slice(start, end);
    },
    allSelected() {
      return this.selectedClients.length === this.paginatedClients.length;
    },
  }),
  methods: {
    ...mapActions("clients", ["deleteClient", "deleteClients"]),
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
        this.selectedClients.push(bulkSelectionAction.id);
      }

      if (bulkSelectionAction.action === "remove") {
        const index = this.selectedClients.indexOf(bulkSelectionAction.id);
        if (index > -1) {
          this.selectedClients.splice(index, 1);
        }
      }
    },
    deleteSelected() {
      if (this.selectedClients.length === 0) {
        return;
      }
      this.$confirm.require({
        message:
          "Are you sure you want to delete " +
          this.selectedClients.length +
          " clients?",
        header: "Delete clients",
        acceptClass: "confirm-button-accept",
        icon: "pi pi-exclamation-triangle",
        accept: () => {
          this.deleteClients(this.selectedClients);
          this.selectedClients = [];
        },
      });
    },
    toggleAll() {
      if (this.selectedClients.length < this.paginatedClients.length) {
        this.selectedClients = this.paginatedClients.map((client) => client.id);
      } else {
        this.selectedClients = [];
      }
    },
    isSelected(clientId) {
      return this.selectedClients.includes(clientId);
    },
  },
  mounted() {
    this.$store.dispatch("client/clearClient");
  },
};
</script>

<style scoped></style>
