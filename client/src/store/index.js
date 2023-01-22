import { createStore, createLogger } from "vuex";
import VueCookies from "vue-cookies";
import router from "../router";

import projects from "./modules/projects";
import project from "@/store/modules/project";
import timer from "@/store/modules/timer";
import api from "@/api/api";

const debug = import.meta.env.NODE_ENV !== "production";

export default createStore({
  state: {
    token: null,
    refreshToken: null,
    authorized: false,
    isLogin: true,
    user: {},
  },
  modules: {
    projects,
    project,
    timer,
  },
  actions: {
    logout({ commit }) {
      commit("setToken", "");
      commit("setRefreshToken", "");
      commit("setAuthorized", false);
      commit("setUser", {});
      VueCookies.remove("token");
      router.push("/login");
    },
    async loadInitial({ commit }) {
      const user = await api.getUser();
      commit("setUser", user);
      const timer = await api.getTimer();
      if (timer && timer.id) {
        commit("timer/setTimer", timer);
      }
    },
  },
  mutations: {
    setToken(state, token) {
      VueCookies.set("api_token", token, "1d", "/", null, true, "Strict");

      state.token = token;
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
    isAuthorized(state, getters) {
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
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
