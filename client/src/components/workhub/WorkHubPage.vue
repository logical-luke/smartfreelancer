<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Work Hub") }}</h3>
    </div>
    <div class="flex justify-between gap-4 flex-wrap items-center">
      <div class="flex gap-2 flex-wrap">
        <new-button go-to="/client/create/">
          <template v-slot:icon><user-plus-icon size="20" /></template>
          client
        </new-button>
        <new-button go-to="/project/create/">
          <template v-slot:icon><folder-plus-icon size="20" /></template>
          project
        </new-button>
        <new-button go-to="/task/create/">
          <template v-slot:icon><clipboard-plus-icon size="20" /></template>
          task
        </new-button>
      </div>
    </div>
    <div class="flex flex-wrap items-center gap-4 py-6">
      <button
        type="button"
        class="inline-flex text-center items-center px-3 flex-nowrap py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
        label="Expand all"
        @click="expandAll"
      >
        Expand all
      </button>
      <button
        type="button"
        class="inline-flex text-center items-center px-3 flex-nowrap py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
        label="Collapse all"
        @click="collapseAll"
      >
        Collapse all
      </button>
    </div>
    <div class="flex justify-between py-4 gap-4 flex-wrap items-center">
      <Tree
        :value="workhubNodes"
        v-model:selectionKeys="selectedKey"
        selectionMode="checkbox"
        v-model:expandedKeys="expandedKeys"
        :filter="true"
        filterMode="lenient"
        class="w-full md:w-30rem"
      >
      </Tree>
    </div>
  </div>
</template>

<script>
import TreeTable from "primevue/treetable";
import Column from "primevue/column";
import Card from "primevue/card";
import Button from "primevue/button";
import NewButton from "@/components/ui/NewButton.vue";
import Tree from "primevue/tree";
import UserPlusIcon from "vue-tabler-icons/icons/UserPlusIcon";
import ClipboardPlusIcon from "vue-tabler-icons/icons/ClipboardPlusIcon";
import FolderPlusIcon from "vue-tabler-icons/icons/FolderPlusIcon";
import { mapState } from "vuex";

export default {
  name: "WorkHubPage",
  components: {
    ClipboardPlusIcon,
    FolderPlusIcon,
    UserPlusIcon,
    NewButton,
    Button,
    Card,
    TreeTable,
    Column,
    Tree,
  },
  computed: {
    ...mapState({
      projects: (state) => state.projects.all,
      clients: (state) => state.clients.all,
      tasks: (state) => state.tasks.all,
    }),
  },
  data() {
    return {
      selectedKey: null,
      expandedKeys: {},
      workhubNodes: [],
    };
  },
  watch: {
    projects() {
      this.updateNodes();
    },
    clients() {
      this.updateNodes();
    },
    tasks() {
      this.updateNodes();
    },
  },
  methods: {
    expandAll() {
      for (let node of this.workhubNodes) {
        this.expandNode(node);
      }

      this.expandedKeys = { ...this.expandedKeys };
    },
    collapseAll() {
      this.expandedKeys = {};
    },
    expandNode(node) {
      if (node.children && node.children.length) {
        this.expandedKeys[node.key] = true;

        for (let child of node.children) {
          this.expandNode(child);
        }
      }
    },
    updateNodes() {
      let nodesMockup = [
        {
          key: "0",
          label: "Documents",
          data: "Documents Folder",
          icon: "pi pi-fw pi-inbox",
          children: [
            {
              key: "0-0",
              label: "Work",
              data: "Work Folder",
              icon: "pi pi-fw pi-cog",
              children: [
                {
                  key: "0-0-0",
                  label: "Expenses.doc",
                  icon: "pi pi-fw pi-file",
                  data: "Expenses Document",
                },
                {
                  key: "0-0-1",
                  label: "Resume.doc",
                  icon: "pi pi-fw pi-file",
                  data: "Resume Document",
                },
              ],
            },
            {
              key: "0-1",
              label: "Home",
              data: "Home Folder",
              icon: "pi pi-fw pi-home",
              children: [
                {
                  key: "0-1-0",
                  label: "Invoices.txt",
                  icon: "pi pi-fw pi-file",
                  data: "Invoices for this month",
                },
              ],
            },
          ],
        },
        {
          key: "1",
          label: "Events",
          data: "Events Folder",
          icon: "pi pi-fw pi-calendar",
          children: [
            {
              key: "1-0",
              label: "Meeting",
              icon: "pi pi-fw pi-calendar-plus",
              data: "Meeting",
            },
            {
              key: "1-1",
              label: "Product Launch",
              icon: "pi pi-fw pi-calendar-plus",
              data: "Product Launch",
            },
            {
              key: "1-2",
              label: "Report Review",
              icon: "pi pi-fw pi-calendar-plus",
              data: "Report Review",
            },
          ],
        },
        {
          key: "2",
          label: "Movies",
          data: "Movies Folder",
          icon: "pi pi-fw pi-star-fill",
          children: [
            {
              key: "2-0",
              icon: "pi pi-fw pi-star-fill",
              label: "Al Pacino",
              data: "Pacino Movies",
              children: [
                {
                  key: "2-0-0",
                  label: "Scarface",
                  icon: "pi pi-fw pi-video",
                  data: "Scarface Movie",
                },
                {
                  key: "2-0-1",
                  label: "Serpico",
                  icon: "pi pi-fw pi-video",
                  data: "Serpico Movie",
                },
              ],
            },
            {
              key: "2-1",
              label: "Robert De Niro",
              icon: "pi pi-fw pi-star-fill",
              data: "De Niro Movies",
              children: [
                {
                  key: "2-1-0",
                  label: "Goodfellas",
                  icon: "pi pi-fw pi-video",
                  data: "Goodfellas Movie",
                },
                {
                  key: "2-1-1",
                  label: "Untouchables",
                  icon: "pi pi-fw pi-video",
                  data: "Untouchables Movie",
                },
              ],
            },
          ],
        },
      ];

      let nodes = [];
      let mainNodesKey = 0;

      if (this.tasks) {
        this.tasks
          .filter((task) => !task.projectId)
          .forEach((task) => {
            nodes.push({
              key: mainNodesKey,
              label: task.name,
            });
            mainNodesKey++;
          });
      }

      this.workhubNodes = nodes;
    }
  },
  mounted() {
    this.updateNodes();
  }
};
</script>

<style scoped></style>
