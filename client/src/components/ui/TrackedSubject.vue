<template>
  <briefcase-icon />
  <div class="w-60">
    <treeselect
      v-model="subject"
      :multiple="false"
      :options="options"
      placeholder="Select Task/Project/Client"
    />
<!--    <v-select-->
<!--      :options="getProjectsNames"-->
<!--      @update:modelValue="updateProjectName"-->
<!--      placeholder="Project"-->
<!--      label="name"-->
<!--      v-model="projectName"-->
<!--      class="project-selector"-->
<!--    >-->
<!--    </v-select>-->
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import store from "@/store";
import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

export default {
  name: "TrackedSubject",
  components: { Treeselect },
  data() {
    return {
      subject: null,
      // define options
      options: [ {
        id: 'a',
        label: 'a',
        children: [ {
          id: 'aa',
          label: 'aa',
        }, {
          id: 'ab',
          label: 'ab',
        } ],
      }, {
        id: 'b',
        label: 'b',
      }, {
        id: 'c',
        label: 'c',
      } ],
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
      clients: (state) => state.clients.all,
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
