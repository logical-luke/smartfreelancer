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
        selectionMode="multiple"
        v-model:expandedKeys="expandedKeys"
        :filter="true"
        filterMode="lenient"
        class="w-full md:w-30rem"
        :paginator="true"
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
    toggleSelectMode() {
        this.selectMode = !this.selectMode;
    },
    getTreeSelectionMode() {
      return this.selectMode ? 'multiple' : 'single';
    },
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
      let nodes = [];
      let mainNodesKey = 0;

      if (this.tasks) {
        this.tasks
          .filter((task) => !task.projectId)
          .forEach((task) => {
            nodes.push({
              key: task.id,
              label: task.name,
              icon: "pi pi-file",
              data: task.id,
            });
            mainNodesKey++;
          });
      }

      if (this.projects) {
        this.projects
          .filter((project) => !project.clientId)
          .forEach((project) => {
            let childrenNodesKey = 0;
            const children = this.tasks
              .filter((task) => task.projectId === project.id)
              .map((task) => {
                return {
                  key: task.id,
                  label: task.name,
                  icon: "pi pi-file",
                  data: task.id,
                };
              });
            let projectNode = {
              key: project.id,
              label: project.name,
              icon: "pi pi-briefcase",
              data: project.id,
            };
            if (children) {
              projectNode.children = children;
            }
            nodes.push(projectNode);
          });
      }

      if (this.clients) {
        this.clients.forEach((client) => {
          nodes.push({
            key: client.id,
            label: client.name,
            icon: "pi pi-user",
            data: client.id,
          });
          mainNodesKey++;
        });
      }

      this.workhubNodes = nodes;
    },
  },
  mounted() {
    this.updateNodes();
  },
};
</script>

<style scoped>
</style>
