import { createStore, createLogger } from "vuex";
import VueCookies from "vue-cookies";
import router from "../router";

import projects from "./modules/projects";
import project from "@/store/modules/project";
import timer from "@/store/modules/timer";
import api from "@/services/api/api";
import clients from "@/store/modules/clients";
import client from "@/store/modules/client";
import tasks from "@/store/modules/tasks";
import task from "@/store/modules/task";
import timeEntries from "@/store/modules/timeEntries";
import { getServerTime } from "@/services/time/serverTimeGetter";

const debug = process.env.NODE_ENV !== "production";

export default createStore({
  state: {
    token: null,
    refreshToken: null,
    authorized: false,
    isLogin: true,
    user: {},
    initialLoaded: false,
    synchronised: false,
    serverTime: null,
  },
  modules: {
    projects,
    project,
    timer,
    clients,
    client,
    tasks,
    task,
    timeEntries,
  },
  actions: {
    async logout({ commit }) {
      commit("setToken", "");
      commit("setRefreshToken", "");
      commit("setInitialLoaded", true);
      commit("setAuthorized", false);
      commit("setUser", {});
      commit("setSynchronised", false);
      VueCookies.remove("token");
      VueCookies.remove("refresh_token");
      localStorage.clear();
      await router.push("/login");
    },
    async loadInitial({ commit, dispatch }) {
      let user = null;

      try {
        user = await api.getUser();
      } catch (err) {
        return dispatch("logout");
      }

      if (!user) {
        return dispatch("logout");
      }
      commit("setUser", user);

      dispatch("getNetworkTime");

      let timer = null;
      if (localStorage.getItem("timer")) {
        timer = JSON.parse(localStorage.getItem("timer"));
      }
      if (timer && timer.id) {
        commit("timer/setTimer", timer);
      }
      let projects = null;
      if (localStorage.getItem("projects")) {
        projects = JSON.parse(localStorage.getItem("projects"));
      }
      if (projects) {
        commit("projects/setProjects", projects);
      }
      let clients = null;
      if (localStorage.getItem("clients")) {
        clients = JSON.parse(localStorage.getItem("clients"));
      }
      if (clients) {
        commit("clients/setClients", clients);
      }
      let tasks = null;
      if (localStorage.getItem("tasks")) {
        tasks = JSON.parse(localStorage.getItem("tasks"));
      }
      if (tasks) {
        commit("tasks/setTasks", tasks);
      }

      commit("setInitialLoaded", true);
    },
    async sync({ commit, state, dispatch }) {
      const clientsFetched = dispatch("clients/getClients");
      const projectsFetched = dispatch("projects/getProjects");
      const tasksFetched = dispatch("tasks/getTasks");
      const timerFetched = dispatch("timer/getTimer");
      Promise.all([
        clientsFetched,
        projectsFetched,
        tasksFetched,
        timerFetched,
      ]).then(() => {
        commit("setSynchronised", true);
      });
    },
    async getNetworkTime({ commit }) {
      const serverTime = await getServerTime();
      commit("setServerTime", serverTime);
    },
  },
  mutations: {
    setToken(state, token) {
      VueCookies.set("api_token", token, "1d", "/", null, true, "Strict");

      state.token = token;
    },
    setInitialLoaded(state, loaded) {
      state.initialLoaded = loaded;
    },
    setSynchronised(state, synchronised) {
      state.synchronised = synchronised;
    },
    setRefreshToken(state, refreshToken) {
      VueCookies.set(
        "refresh_token",
        refreshToken,
        "1d",
        "/",
        null,
        true,
        "Strict"
      );

      state.refreshToken = refreshToken;
    },
    setAuthorized(state, authorized) {
      state.authorized = authorized;
    },
    setUser(state, user) {
      state.user = user;
    },
    setServerTime(state, serverTime) {
      state.serverTime = serverTime;
    },
  },
  getters: {
    isAuthorized(state) {
      return state.authorized;
    },
    getToken(state) {
      return state.token;
    },
    getRefreshToken(state) {
      return state.refreshToken;
    },
    getUserId(state) {
      return state.user.id;
    },
    isInitialLoaded(state) {
      return state.initialLoaded;
    },
    isSynchronised(state) {
      return state.synchronised;
    },
    getServerTime(state) {
      return state.serverTime;
    },
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
