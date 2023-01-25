<template>
  <briefcase-icon />
  <div class="w-60">
    <v-select
      :options="getProjectsNames"
      @update:modelValue="updateProjectName"
      placeholder="Project"
      label="name"
      v-model="projectName"
      class="project-selector"
    >
    </v-select>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import store from "@/store";
import BriefcaseIcon from "vue-tabler-icons/icons/BriefcaseIcon";

export default {
  name: "TrackedProject",
  components: { BriefcaseIcon, vSelect },
  data() {
    return {
      projectName: null,
    };
  },
  watch: {
    timerProjectId() {
      if (this.timerProjectId && this.projects[this.timerProjectId]) {
        this.projectName = this.projects[this.timerProjectId].name;
      } else {
        this.projectName = null;
      }
    },
    projects() {
      if (
        this.timerProjectId &&
        this.projects[this.timerProjectId] &&
        this.projectName.name !== this.projects[this.timerProjectId].name
      ) {
        this.projectName = {
          name: this.projects[this.timerProjectId].name,
          id: this.timerProjectId,
        };
      }
    },
  },
  methods: {
    async updateProjectName(projectName) {
      if (projectName && this.timerProjectId !== projectName.id) {
        await store.dispatch("timer/setProjectId", projectName.id);
      }

      if (this.timerProjectId && !projectName) {
        await store.dispatch("timer/setProjectId", null);
      }
    },
  },
  computed: {
    ...mapState({
      timerProjectId: (state) => state.timer.current.projectId,
      projects: (state) => state.projects.all,
    }),
    ...mapGetters({ getProjectsNames: "projects/getProjectsNamesWithIds" }),
  },
  mounted() {
    if (this.timerProjectId && this.projects[this.timerProjectId]) {
      this.projectName = {
        name: this.projects[this.timerProjectId].name,
        id: this.timerProjectId,
      };
    }
  },
};
</script>
