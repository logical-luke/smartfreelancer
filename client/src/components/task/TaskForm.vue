<template>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="">Name:</label>
    <input
      class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
      type="text"
      id="name"
      name="name"
      :value="task.name"
      @input="updateName"
      placeholder="Breathtaking Application"
    />
  </div>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="">Description:</label>
    <textarea
      class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
      id="name"
      name="description"
      :value="task.description"
      @input="updateDescription"
      rows="5"
      placeholder="Write something..."
    ></textarea>
  </div>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="">Client:</label>
    <SelectClient :selected="task.clientId" @updated="updateClientId" />
  </div>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="">Project:</label>
    <SelectProject :selected="task.projectId" @updated="updateProjectId" />
  </div>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="">Task:</label>
    <SelectTask :selected="task.taskId" @updated="updateTaskId" />
  </div>
</template>

<script>
import { mapState } from "vuex";
import SelectProject from "@/components/ui/SelectProject.vue";
import SelectClient from "../ui/SelectClient.vue";
import SelectTask from "../ui/SelectTask.vue";

export default {
  name: "TaskForm",
  components: {SelectTask, SelectClient, SelectProject },
  computed: mapState({
    task: (state) => state.task.current,
  }),
  methods: {
    updateName(event) {
      this.$store.commit("task/setName", event.target.value);
    },
    updateDescription(event) {
      this.$store.commit("task/setDescription", event.target.value);
    },
    updateProjectId(projectId) {
      this.$store.dispatch("task/setProjectId", projectId);
    },
    updateClientId(clientId) {
      this.$store.dispatch("task/setClientId", clientId);
    },
    updateTaskId(taskId) {
      this.$store.dispatch("task/setTaskId", taskId);
    },
  },
};
</script>

<style scoped></style>
