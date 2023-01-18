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
      VueCookies.set("token", null, "1d", "/", null, true, "Strict");
      VueCookies.remove("token");
      VueCookies.set("refresh_token", null, "1d", "/", null, true, "Strict");
      VueCookies.remove("refresh_token");

      commit('setToken', null);
      commit('setRefreshToken', null);

      router.push("/login");
    }
  },
  mutations: {
    setToken(state, token) {
      VueCookies.set("token", token, "1d", "/", null, true, "Strict");

      state.token = token;
    },
    setRefreshToken(state, token) {
      VueCookies.set("refresh_token", token, "1d", "/", null, true, "Strict");

      state.refreshToken = token;
    },
  },
  getters: {
    isLoggedIn(state, getters) {
      return getters.getToken !== null;
    },
    getToken(state) {
      let token = state.token;
      if (!token) {
        token = VueCookies.get("token");
      }
      if (token === 'null') {
        token = null;
      }

      return token;
    },
    getRefreshToken(state) {
      let token = state.refreshToken;
      if (!token) {
        token = VueCookies.get("refresh_token");
      }
      if (token === 'null') {
        token = null;
      }

      return token;
    },
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
