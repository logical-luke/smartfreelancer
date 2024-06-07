<template>
  <v-select
    :options="options"
    placeholder="Select project"
    v-model="projectId"
    @update:modelValue="setProject"
  >
  </v-select>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { onMounted, ref } from "vue";
import store from "@/store";

export default {
  name: "SelectProject",
  components: { vSelect },
  props: {
    selected: Number,
  },
  watch: {
    projects() {
      this.options = store.getters["projects/getProjectsOptions"];
    },
  },
  methods: {
    setProject(project) {
      if (project) {
        return this.$emit("updated", project.id);
      }

      return this.$emit("updated", null);
    },
  },
  setup(props) {
    let projectId = ref("projectId");
    let options = ref("options");
    projectId.value = null;
    options.value = store.getters["projects/getProjectsOptions"];
    onMounted(() => {
      if (props.selected) {
        const selectedProject = options.value
          .filter((project) => project.id === props.selected)
          .pop();
        projectId.value = {
          id: selectedProject.id,
          label: selectedProject.label,
        };
      }
    });
    return {
      projectId,
      options,
    };
  },
  emits: ["updated"],
  created() {
    this.options = store.getters["projects/getProjectsOptions"];
  },
};
</script>

<style scoped></style>
