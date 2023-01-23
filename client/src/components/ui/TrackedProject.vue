<template>
  <briefcase-icon />
  <div class="w-32">
    <v-select
      :options="getProjectsNames"
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
    }
  },
  watch: {
    timer() {
      if (
        this.timer
        && this.timer.projectId
        && this.projects[this.timer.projectId]
      ) {
        this.projectName = this.projects[this.timer.projectId].name;
      } else {
        this.projectName = null;
      }
    },
    projectName() {
      if (this.timer && !this.projectName) {
        store.commit('timer/updateProjectId', null);
      }

      if (this.projectName && this.timer.projectId !== this.projectName.id) {
        store.commit('timer/updateProjectId', this.projectName.id);
      }
    }
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      projects: (state) => state.projects.all,
      subject: (state) => state.timer.current.subjectName
    }),
    ...mapGetters({ getProjectsNames: "projects/getProjectsNamesWithIds" })
  },
  mounted() {
    if (
      this.timer
      && this.timer.projectId
      && this.projects[this.timer.projectId]
    ) {
      this.projectName = this.projects[this.timer.projectId].name;
    }
  }
};
</script>

