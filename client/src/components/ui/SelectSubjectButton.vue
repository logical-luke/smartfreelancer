<template>
  <div @click="toggle">
    <button
        v-if="!this.subject"
        type="button"
        class="inline-flex bg-indigo-500 hover:bg-indigo-600 justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
    >
      <folder-share-icon :size="Math.ceil(size * 1.9)"/>
    </button>
    <tag v-else :icon="getSubjectIcon()">{{ this.subject.name }}</tag>
  </div>
  <overlay-panel ref="op">
    <tree
        :metaKeySelection="false"
        selectionMode="single"
        :filter="true"
        filterMode="lenient"
        v-model:selectionKeys="selectedKeys"
        :value="options"
        class="w-full md:w-30rem"
        @nodeSelect="onNodeSelect"
    />
  </overlay-panel>
</template>

<script>
import {mapState} from "vuex";
import "vue3-acies-treeselect/dist/vue3-treeselect.css";
import store from "@/store";
import timer from "@/services/timer";
import OverlayPanel from 'primevue/overlaypanel';
import Tag from 'primevue/tag';
import FolderShareIcon from "vue-tabler-icons/icons/FolderShareIcon";
import Tree from 'primevue/tree';

export default {
  name: "SelectSubjectButton",
  components: {FolderShareIcon, Tree, OverlayPanel, Tag},
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
      selectedKeys: [],
    };
  },
  watch: {
    timer() {
      this.setSubjectFromTimer(this.timer);
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
    locale() {
      this.options[0].label = this.$t('No target');
    }
  },
  methods: {
    getSubjectIcon() {
      if (this.subject) {
        if (this.subject.type === "client") {
          return "pi pi-user";
        }
        if (this.subject.type === "project") {
          return "pi pi-briefcase";
        }
        if (this.subject.type === "task") {
          return "pi pi-file";
        }

        return "";
      } else {
        return "";
      }
    },
    setSubjectFromTimer(timer) {
      this.subject = null;
      let subject = {};
      if (timer.clientId) {
        subject.id = timer.clientId;
        subject.type = "client";
        subject.name = store.getters["clients/getClientById"](timer.clientId).name;
        this.subject = subject;
      }
      if (timer.projectId) {
        subject.id = timer.projectId;
        subject.type = "project";
        subject.name = store.getters["projects/getProjectById"](timer.projectId).name;
        this.subject = subject;
      }
      if (timer.taskId) {
        subject.id = timer.taskId;
        subject.type = "task";
        subject.name = store.getters["tasks/getTaskById"](timer.taskId).name;
        this.subject = subject;
      }
    },
    async onNodeSelect(node) {
      this.$refs.op.hide();
      await this.setTimerSubject(node)
      if (node.type === "noSubject") {
        this.selectedKeys = null;
      }
    },
    async setTimerSubject(value) {
      if (value.type === "project") {
        await timer.setProject(value.data);
      }
      if (value.type === "client") {
        await timer.setClient(value.data);
      }
      if (value.type === "task") {
        await timer.setTask(value.data);
      }
      if (value.type === "noSubject") {
        await timer.setEmptySubject();
      }
    },
    findKeyById(id) {
      let matchById = (options) => {
        return options.filter((option) => option.id === id).pop()
      };

      return matchById(this.options).key;
    },
    updateSubjectOptions() {
      const formatNodes = (nodes, prefix = '', isFirstIteration = true, depth = 0) => {
        let counter = isFirstIteration ? 1 : 0;
        return nodes.map((node, i) => {
          const newPrefix = depth > 0 ? `${prefix}-${i - counter}` : `${prefix}${i + 1}`;
          counter++;

          let icon = '';
          switch (node.type) {
            case 'client':
              icon = 'pi-user';
              break;
            case 'project':
              icon = 'pi-briefcase';
              break;
            case 'task':
              icon = 'pi-file';
              break;
            default:
              break;
          }

          return {
            key: newPrefix,
            data: node.id,
            label: node.name,
            type: node.type,
            icon: 'pi pi-fw ' + icon,
            selectable: true,
            children: formatNodes(node.children, newPrefix, false, depth + 1),
          };
        });
      }

      this.options = [{
        key: '0',
        data: null,
        label: this.$t('No target'),
        type: 'noSubject',
        selectable: true,
      }, ...formatNodes(store.getters["getWorkHubTree"], '', true, 0)];
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
      locale : (state) => state.settings.locale,
    }),
  },
  mounted() {
    this.updateSubjectOptions();
    this.setSubjectFromTimer(this.timer);
  },
};
</script>
