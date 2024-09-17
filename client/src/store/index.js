import { createStore, createLogger } from "vuex";

import authorization from "@/store/modules/authorization";
import clients from "@/store/modules/clients";
import projects from "./modules/projects";
import settings from "@/store/modules/settings";
import tasks from "@/store/modules/tasks";
import time from "@/store/modules/time";
import timeEntries from "@/store/modules/timeEntries";
import timer from "@/store/modules/timer";
import buildWorkHubTree from "@/services/treeBuilder";
import pomodoro from "@/store/modules/pomodoro";
const debug = process.env.NODE_ENV !== "production";

export default createStore({
  state: {
    user: {},
  },
  modules: {
    authorization,
    clients,
    projects,
    settings,
    tasks,
    time,
    timeEntries,
    timer,
    pomodoro,
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
  },
  getters: {
    getUserId(state) {
      return state.user.id;
    },
    getWorkHubTree(state, getters, rootState, rootGetters) {
      return buildWorkHubTree(
        rootGetters["clients/getClients"],
        rootGetters["projects/getProjects"],
        rootGetters["tasks/getTasks"]
      );
    },
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
