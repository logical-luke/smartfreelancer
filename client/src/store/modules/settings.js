const state = () => ({
  navBarCollapsed: false,
  locale: "en",
});

const actions = {
  setLocale({ commit }, locale) {
    commit("setLocale", locale);
  },
}

const mutations = {
  toggleNavBarCollapsed: state => state.navBarCollapsed = !state.navBarCollapsed,
  setLocale(state, locale) {
    state.locale = locale;
  },
}

const getters = {
  isNavBarCollapsed: state => state.navBarCollapsed,
  getLocale: state => state.locale,
}

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
}
