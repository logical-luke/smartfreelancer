<template>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="">Name:</label>
    <input
      class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
      type="text"
      id="name"
      name="name"
      :value="project.name"
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
      :value="project.description"
      @input="updateDescription"
      rows="5"
      placeholder="Write something..."
    ></textarea>
  </div>
  <div class="mb-6">
    <label class="block text-sm font-medium mb-2" for="">Client:</label>
    <SelectClient :selected="project.clientId" @updated="updateClientId" />
  </div>

</template>

<script>
import { mapState } from "vuex";
import SelectClient from "@/components/ui/SelectClient.vue";

export default {
  name: "ProjectForm",
  components: { SelectClient },
  computed: mapState({
    project: (state) => state.project.current,
  }),
  methods: {
    updateName(event) {
      this.$store.commit("project/setName", event.target.value);
    },
    updateDescription(event) {
      this.$store.commit("project/setDescription", event.target.value);
    },
    updateClientId(clientId) {
      this.$store.dispatch("project/setClientId", clientId);
    }
  },
};
</script>

<style scoped></style>
