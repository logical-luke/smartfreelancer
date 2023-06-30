<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Work Hub") }}</h3>
    </div>
    <div class="flex justify-between gap-4 flex-wrap items-center">
      <div class="flex flex-wrap gap-2 flex-wrap">
        <new-button go-to="/client/create/">client</new-button>
        <new-button go-to="/project/create/">project</new-button>
        <new-button go-to="/task/create/">task</new-button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import TreeTable from "primevue/treetable";
import Column from "primevue/column";
import Card from "primevue/card";
import Button from "primevue/button";
import NewButton from "@/components/ui/NewButton.vue";

export default {
  name: "WorkHubPage",
  components: { NewButton, Button, Card, TreeTable, Column },
  setup() {
    const nestedItems = ref([]);

    // Get nestedItems from store for clients, projects and tasks
    // and convert them to the format required by TreeTable component

    const showDialog = (type) => {
      // Show dialog based on the type (client, project or task)
    };

    const actionTemplate = (node) => {
      if (!node) return;
      // Return the action buttons here for Details and Delete
    };

    return { nestedItems, showDialog, actionTemplate };
  },
};
</script>

<style scoped></style>
