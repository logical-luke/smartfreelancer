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

const debug = import.meta.env.NODE_ENV !== "production";

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
    task
  },
  actions: {
    logout({ commit }) {
      commit("setToken", "");
      commit("setRefreshToken", "");
      commit("setAuthorized", false);
      commit("setUser", {});
      VueCookies.remove("token");
      VueCookies.remove("refresh_token");
      localStorage.clear();
      router.push("/login");
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
      if (localStorage.getItem('timer')) {
        timer = JSON.parse(localStorage.getItem('timer'));
      }
      if (timer && timer.id) {
        commit("timer/setTimer", timer);
      }
      let projects = null;
      if (localStorage.getItem('projects')) {
        projects = JSON.parse(localStorage.getItem('projects'));
      }
      if (projects) {
        commit("projects/setProjects", projects);
      }
      let clients = null;
      if (localStorage.getItem('clients')) {
        clients = JSON.parse(localStorage.getItem('clients'));
      }
      if (clients) {
        commit("clients/setClients", clients);
      }
      let tasks = null;
      if (localStorage.getItem('tasks')) {
        tasks = JSON.parse(localStorage.getItem('tasks'));
      }
      if (tasks) {
        commit("tasks/setTasks", tasks);
      }
      if (!projects || !clients || !tasks) {
        await dispatch("syncInitial");
      }

      commit("setInitialLoaded", true);
      await dispatch("syncInitial");
    },
    async syncInitial({ commit, state, dispatch }) {
      if (state.synchronised) {
        return;
      }
      const timer = await api.getTimer();
      if (timer && timer.id) {
        commit("timer/setTimer", timer);
      }
      await dispatch("projects/getProjects");
      await dispatch("clients/getClients");
      await dispatch("tasks/getTasks");
      commit("setSynchronised", true);
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
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
