<template>
  <div class="w-92">
    <treeselect
      v-model="subject"
      :multiple="false"
      :options="options"
      :show-count="true"
      @update:modelValue="setSubject"
      placeholder="Select task/project/client..."
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
      options: []
    };
  },
  watch: {
    timer() {
      this.updateSubject();
    },
    projects() {
      this.updateSubjectOptions();
    },
    clients() {
      this.updateSubjectOptions();
    },
    tasks() {
      this.updateSubjectOptions();
    }
  },
  methods: {
    updateSubject() {
      if (this.timer.clientId) {
        this.subject = "c-" + this.timer.clientId;

        return;
      }

      if (this.timer.projectId) {
        this.subject = "p-" + this.timer.projectId;
        const project = store.getters["projects/getProjectById"](this.timer.projectId);
        if (project.clientId) {
          const clientOption = this.options.find(option => Number(option.id.split("-")[1]) === project.clientId);
          if (clientOption) {
            clientOption.isDefaultExpanded = true;
          }
        }

        return;
      }

      if (this.timer.taskId) {
        this.subject = "t-" + this.timer.taskId;
        const task = store.getters["tasks/getTaskById"](this.timer.taskId);
        if (task.projectId) {
          const projectOption = this.options.find(option => Number(option.id.split("-")[1]) === task.projectId);
          if (projectOption) {
            projectOption.isDefaultExpanded = true;
          }
        }

        return;
      }

      this.subject = null;
    },
    async setSubject(value) {
      if (value == null) {
        await store.dispatch("timer/setProjectId", null);
        await store.dispatch("timer/setClientId", null);
        await store.dispatch("timer/setTaskId", null);

        return;
      }

      let id = Number(value.split("-")[1]);
      if (value.startsWith("p-")) {
        await store.dispatch("timer/setProjectId", id);
      }
      if (value.startsWith("c-")) {
        await store.dispatch("timer/setClientId", id);
      }
      if (value.startsWith("t-")) {
        await store.dispatch("timer/setTaskId", id);
      }
    },
    updateSubjectOptions() {
      let options = [];

      if (this.tasks) {
        this.tasks
          .filter((task) => !task.projectId)
          .forEach((task) => {
            options.push({
              id: "t-" + task.id,
              label: "📋 " + task.name
            });
          });
      }

      if (this.projects) {
        this.projects
          .filter((project) => !project.clientId)
          .forEach((project) => {
            const projectOption = {
              id: "p-" + project.id,
              label: "💼 " + project.name
            };
            const children = this.tasks
              .filter((task) => task.projectId === project.id)
              .map((task) => {
                return {
                  id: "t-" + task.id,
                  label: "📋 " + task.name
                };
              });
            if (children.length > 0) {
              projectOption.children = children;
            }
            options.push(projectOption);
          });
      }

      if (this.clients) {
        this.clients.forEach((client) => {
          const clientOption = {
            id: "c-" + client.id,
            label: "👤 " + client.name
          };
          const children = this.projects
            .filter((project) => project.clientId === client.id)
            .map((project) => {
              const projectOption = {
                id: "p-" + project.id,
                label: "💼 " + project.name
              };
              const children = this.tasks
                .filter((task) => task.projectId === project.id)
                .map((task) => {
                  return {
                    id: "t-" + task.id,
                    label: "📝 " + task.name
                  };
                });
              if (children.length > 0) {
                projectOption.children = children;
              }

              return projectOption;
            });
          if (children.length > 0) {
            clientOption.children = children;
          }
          options.push(clientOption);
        });
      }

      this.options = options;
    }
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      projects: (state) => state.projects.all,
      clients: (state) => state.clients.all,
      tasks: (state) => state.tasks.all
    }),
  },
  mounted() {
    this.updateSubjectOptions();
    this.updateSubject();
  }
};
</script>
