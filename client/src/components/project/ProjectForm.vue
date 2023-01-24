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
</template>

<script>
import { mapMutations, mapState } from "vuex";
import api from "@/api/api";

export default {
  name: "ProjectForm",
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
  },
};
</script>

<style scoped></style>
