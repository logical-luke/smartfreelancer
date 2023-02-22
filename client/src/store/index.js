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
import getServerTime from "@/services/time/serverTimeGetter";

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
    serverTimeSyncId: null,
    navBarCollapsed: false,
    locale: "en",
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
      commit("setAuthorized", false);
      commit("setUser", {});
      commit("setSynchronised", false);
      VueCookies.remove("token");
      VueCookies.remove("refresh_token");
      localStorage.clear();
      await router.push("/login");
    },
    async login({ commit, dispatch }, payload) {
      let token = null;
      let refreshToken = null;
      if (payload.email && payload.password) {
        try {
          const tokens = await api.login(payload.email, payload.password);
          token = tokens.token;
          refreshToken = tokens.refreshToken;
        } catch (error) {
          throw new Error(error.message);
        }
      }
      if (payload.token && payload.refreshToken) {
        token = payload.token;
        refreshToken = payload.refreshToken;
      }
      commit("setInitialLoaded", false);
      if (token && refreshToken) {
        commit("setToken", token);
        commit("setRefreshToken", refreshToken);
        commit("setAuthorized", true);
        await dispatch("sync");
        await dispatch("enableServerTimeSync");

        commit("setInitialLoaded", true);

        return router.push("/");
      }
      commit("setInitialLoaded", true);
    },
    async register({ dispatch }, credentials) {
      try {
        await api.register(
          credentials.email,
          credentials.password,
          credentials.confirmPassword
        );
        dispatch("login", {
          email: credentials.email,
          password: credentials.password,
        });
      } catch (err) {
        console.log(err);
        throw new Error(err.message);
      }
    },
    async loadInitial({ commit, dispatch }) {
      let user = null;

      try {
        user = await api.getUser();
      } catch (err) {
        await dispatch("logout");

        return;
      }

      if (!user) {
        await dispatch("logout");

        return
      }
      commit("setUser", user);

      await dispatch("enableServerTimeSync");

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
    async enableServerTimeSync({ commit, dispatch, rootGetters }) {
      await dispatch("getServerTime");
      const serverTimeSyncId = setInterval(() => {
        if (rootGetters.getServerTime) {
          commit("setServerTime", Number(rootGetters.getServerTime) + 1);
        }
      }, 1000);
      commit("setServerTimeSyncId", serverTimeSyncId);
    },
    async disableServerTimeSync({ commit, rootGetters }) {
      clearInterval(rootGetters.serverTimeSyncId);
      commit("setServerTimeSyncId", null);
    },
    async sync({ commit, dispatch }) {
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
    async getServerTime({ commit }) {
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
    setServerTimeSyncId(state, serverTimeSyncId) {
      state.serverTimeSyncId = serverTimeSyncId;
    },
    toggleNavBarCollapsed(state) {
      state.navBarCollapsed =!state.navBarCollapsed;
    }
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
    isNavBarCollapsed(state) {
      return state.navBarCollapsed;
    },
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
