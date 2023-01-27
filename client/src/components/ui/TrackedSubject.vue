<template>
  <div class="w-60">
    <treeselect
      v-model="subject"
      :multiple="false"
      :options="options"
      @update:modelValue="setSubject"
      placeholder="Select Task/Project/Client"
    />
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import Treeselect from "vue3-acies-treeselect";
import "vue3-acies-treeselect/dist/vue3-treeselect.css";
import store from "@/store";

export default {
  name: "TrackedSubject",
  components: { Treeselect },
  data() {
    return {
      subject: null,
      options: [],
    };
  },
  watch: {
    timerProjectId() {
      this.updateSubject();
    },
    projects() {
      this.updateSubjectOptions();
    },
  },
  methods: {
    updateSubject() {
      if (this.timerProjectId !== null) {
        this.subject = "p-" + this.timerProjectId;

        return;
      }

      this.subject = null;
    },
    async setSubject(value) {
      if (value == null) {
        await store.dispatch("timer/setProjectId", null);

        return;
      }

      let id = Number(value.split("-")[1]);
      if (value.startsWith("p-")) {
        await store.dispatch("timer/setProjectId", id);
      }
    },
    updateSubjectOptions() {
      let options = [];

      if (this.projects) {
        this.projects.forEach((project) => {
          options.push({
            id: "p-" + project.id,
            label: project.name,
          });
        });
      }

      this.options = options;
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
    this.updateSubjectOptions();
    this.updateSubject();
  },
};
</script>
