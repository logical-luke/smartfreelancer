<template>
  <v-select
    :options="options"
    placeholder="Select task"
    v-model="taskId"
    @update:modelValue="setTask"
  >
  </v-select>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { onMounted, ref } from "vue";
import store from "@/store";

export default {
  name: "SelectTask",
  components: { vSelect },
  props: {
    selected: Number,
  },
  watch: {
    tasks() {
      this.options = store.getters["tasks/getTasksOptions"];
    },
  },
  methods: {
    setTask(task) {
      if (task) {
        return this.$emit("updated", task.id);
      }

      return this.$emit("updated", null);
    },
  },
  setup(props) {
    let taskId = ref("taskId");
    let options = ref("options");
    taskId.value = null;
    options.value = store.getters["tasks/getTasksOptions"];
    onMounted(() => {
      if (props.selected) {
        const selectedTask = options.value
          .filter((task) => task.id === props.selected)
          .pop();
        taskId.value = {
          id: selectedTask.id,
          label: selectedTask.label,
        };
      }
    });
    return {
      taskId,
      options,
    };
  },
  emits: ["updated"],
  created() {
    this.options = store.getters["tasks/getTasksOptions"];
  },
};
</script>

<style scoped></style>
