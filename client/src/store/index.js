import { createStore, createLogger } from "vuex";
import VueCookies from "vue-cookies";
import router from "../router";

import projects from "./modules/projects";
import project from "@/store/modules/project";
import timer from "@/store/modules/timer";
import api from "@/api/api";
import clients from "@/store/modules/clients";
import client from "@/store/modules/client";
import tasks from "@/store/modules/tasks";
import task from "@/store/modules/task";

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
  },
  modules: {
    projects,
    project,
    timer,
    clients,
    client,
    tasks,
    task,
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
      if (
        !localStorage.getItem("clients") ||
        !localStorage.getItem("tasks") ||
        !localStorage.getItem("tasks")
      ) {
        await dispatch("syncInitial");
      }

      commit("setInitialLoaded", true);
    },
    async syncInitial({ commit, state, dispatch }) {
      if (state.synchronised) {
        return;
      }
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
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
