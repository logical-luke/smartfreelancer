import { createStore, createLogger } from "vuex";
import projects from "./modules/projects";
import VueCookies from "vue-cookies";
import router from "../router";

const debug = import.meta.env.NODE_ENV !== "production";

export default createStore({
  state: {
    token: null,
    refreshToken: null,
  },
  modules: {
    projects,
  },
  actions: {
    logout({ commit }) {
      commit("setToken", '');
      commit("setRefreshToken", '');
      router.push("/login");
    },
  },
  mutations: {
    setToken(state, token) {
      VueCookies.set("api_token", token, "1d", "/", null, true, "Strict");

      state.token = token;
    },
    setRefreshToken(state, refreshToken) {
      VueCookies.set("refresh_token", refreshToken, "1d", "/", null, true, "Strict");

      state.refreshToken = refreshToken;
    },
  },
  getters: {
    isAuthorized(state, getters) {
        return getters.getToken !== null;
    },
    getToken(state) {
      let token = state.token;
      if (!token) {
        token = VueCookies.get("api_token");
      }
      if (token === "null" || token === '') {
        token = null;
      }

      return token;
    },
    getRefreshToken(state) {
      let token = state.refreshToken;
      if (!token) {
        token = VueCookies.get("refresh_token");
      }
      if (token === "null" || token === '') {
        token = null;
      }

      return token;
    },
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
