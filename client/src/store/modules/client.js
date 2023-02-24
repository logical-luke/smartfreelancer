const state = () => ({
  current: {
    name: "",
    description: "",
  },
});

const getters = {};

const actions = {
  async setName({ commit }, name) {
    commit("setName", name);
  },
  async setDescription({ commit }, description) {
    commit("setDescription", description);
  },
  clearClient({ commit }) {
    commit("clearClient");
  },
};

const mutations = {
  setClient(state, client) {
    state.current = client;
  },
  clearClient(state) {
    state.current = {
      name: "",
      description: "",
    };
  },
  setName(state, name) {
    state.current.name = name;
  },
  setDescription(state, description) {
    state.current.description = description;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
