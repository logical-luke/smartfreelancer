import { createStore, createLogger } from "vuex";
import projects from "./modules/projects";
import VueCookies from "vue-cookies";
import router from "../router";

const debug = import.meta.env.NODE_ENV !== "production";

export default createStore({
  state: {
    token: null,
  },
  modules: {
    projects,
  },
  actions: {
    logout({ commit }) {
      VueCookies.remove("token");
      commit('setToken', null);
      router.push("/login");
    }
  },
  mutations: {
    setToken(state, token) {
      VueCookies.set("token", token, "1d", "/", null, true, "Strict");
      state.token = token;
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
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
