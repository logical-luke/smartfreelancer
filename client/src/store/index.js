import { createStore, createLogger } from "vuex";
import projects from "./modules/projects";
import VueCookies from "vue-cookies";

const debug = import.meta.env.NODE_ENV !== "production";

export default createStore({
  state: {
    token: null,
  },
  modules: {
    projects,
  },
  mutations: {
    setToken(state, token) {
      VueCookies.set('token', token, '1d', '/', null, true, 'Strict');
      state.token = token;
    },
  },
  getters: {
    isLoggedIn(state) {
      if (VueCookies.isKey('token')) {
        return true;
      }

      return !!state.token;
    },
    getToken(state) {
      if (VueCookies.isKey('token')) {
        return VueCookies.get('token');
      }

      return state.token;
    }
  },
  strict: debug,
  plugins: debug ? [createLogger()] : [],
});
