<template>
  <list-icon />
  <div class="w-60">
    <v-select :options="[]" placeholder="Task" :value="projectName"> </v-select>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import ListIcon from "vue-tabler-icons/icons/ListIcon";

export default {
  name: "TrackedTask",
  components: { ListIcon, vSelect },
  data() {
    return {
      projectName: null,
    };
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      projects: (state) => state.projects.all,
      subject: (state) => state.timer.current.subjectName,
    }),
    ...mapGetters({ getProjectsNames: "projects/getProjectsNames" }),
  },
};
</script>

<style scoped></style>
