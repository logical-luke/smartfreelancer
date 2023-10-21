<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
    <div class="flex justify-between gap-4 flex-wrap items-center">
      <div class="flex flex-wrap gap-2">
        <new-button go-to="/client/create/">{{ $t("client")}}</new-button>
      </div>
<!--      <search-controls @pattern-changed="setSearchPattern"/>-->
      <paginator :rows="10" :totalRecords="filteredClients.length" :rowsPerPageOptions="[10, 20, 30]"></paginator>
      <div class="flex w-full flex-wrap -m-4">
        <transition-group name="fade-slower" class="transition-element">
          <template
              v-for="(client, index) in paginatedClients"
              :key="client.id"
          >
            <client-item
                :bulkMode="bulkMode"
                @toggle-select="updateBulkItems"
                :selected="isSelected(client.id)"
                :id="client.id"
                :name="client.name"
                :email="client.email"
                :industry="client.industry"
                :description="client.description"
            />
          </template>
        </transition-group>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ClientItem from "@/components/client/ClientItem.vue";
import SearchControls from "@/components/ui/SearchControls.vue";
import BulkEditButton from "@/components/ui/BulkEditButton.vue";
import TrashIcon from "vue-tabler-icons/icons/TrashIcon";
import Paginator from 'primevue/paginator';

export default {
  name: "ClientsPage",
  components: {
    TrashIcon,
    BulkEditButton,
    SearchControls,
    ClientItem,
    NewButton,
    Paginator
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
