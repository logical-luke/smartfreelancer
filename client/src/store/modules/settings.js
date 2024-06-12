const state = () => ({
  locale: "en",
});

const actions = {
  setLocale({ commit }, locale) {
    commit("setLocale", locale);
  },
};

const mutations = {
  setLocale(state, locale) {
    state.locale = locale;
  },
};

const getters = {
  getLocale: (state) => state.locale,
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
