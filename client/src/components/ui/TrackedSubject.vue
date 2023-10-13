<template>
  <button
      type="button"
      @click="toggle"
      class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    <folder-share-icon :size="Math.ceil(size * 1.8)"/>
  </button>
  <overlay-panel ref="op">
    <div :class="width">
      <treeselect
          v-model="subject"
          :multiple="false"
          :options="options"
          :noOptionsText="$t('No tasks/projects/clients')"
          :noResultsText="$t('No results found')"
          zIndex="10"
          :show-count="true"
          search-nested
          @update:modelValue="setSubject"
          :placeholder="$t('Select task/project/client') + '...'"
      />
    </div>
  </overlay-panel>
</template>

<script>
import {mapState} from "vuex";
import Treeselect from "vue3-acies-treeselect";
import "vue3-acies-treeselect/dist/vue3-treeselect.css";
import store from "@/store";
import timer from "@/services/timer";
import OverlayPanel from 'primevue/overlaypanel';
import FolderShareIcon from "vue-tabler-icons/icons/FolderShareIcon";

export default {
  name: "TrackedSubject",
  components: {FolderShareIcon, Treeselect, OverlayPanel},
  props: {
    width: {
      type: String,
      default: "w-auto",
    },
    size: {
      type: Number,
      default: 12,
    },
  },
  data() {
    return {
      subject: null,
      options: [],
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
    },
  },
  methods: {
    updateSubject() {
      if (this.timer.clientId) {
        this.subject = "c-" + this.timer.clientId;

        return;
      }

      if (this.timer.projectId) {
        this.subject = "p-" + this.timer.projectId;
        const project = store.getters["projects/getProjectById"](
            this.timer.projectId
        );
        if (project.clientId) {
          const clientOption = this.options.find(
              (option) => option.id.slice(2) === project.clientId
          );
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
          const projectOption = this.options.find(
              (option) => option.id.slice(2) === task.projectId
          );
          if (projectOption) {
            projectOption.isDefaultExpanded = true;
          }
        }

        return;
      }

      this.subject = null;
    },
    async setSubject(value) {
      await timer.setSubject(value);
    },
    updateSubjectOptions() {
      let options = [];

      if (this.tasks) {
        this.tasks
            .filter((task) => !task.projectId)
            .forEach((task) => {
              options.push({
                id: "t-" + task.id,
                label: "📋 " + task.name,
              });
            });
      }

      if (this.projects) {
        this.projects
            .filter((project) => !project.clientId)
            .forEach((project) => {
              const projectOption = {
                id: "p-" + project.id,
                label: "💼 " + project.name,
              };
              const children = this.tasks
                  .filter((task) => task.projectId === project.id)
                  .map((task) => {
                    return {
                      id: "t-" + task.id,
                      label: "📋 " + task.name,
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
            label: "👤 " + client.name,
          };
          const children = this.projects
              .filter((project) => project.clientId === client.id)
              .map((project) => {
                const projectOption = {
                  id: "p-" + project.id,
                  label: "💼 " + project.name,
                };
                const children = this.tasks
                    .filter((task) => task.projectId === project.id)
                    .map((task) => {
                      return {
                        id: "t-" + task.id,
                        label: "📝 " + task.name,
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
    },
    toggle(event) {
      this.$refs.op.toggle(event);
    },
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      projects: (state) => state.projects.all,
      clients: (state) => state.clients.all,
      tasks: (state) => state.tasks.all,
    }),
  },
  mounted() {
    this.updateSubjectOptions();
    this.updateSubject();
  },
};
</script>
