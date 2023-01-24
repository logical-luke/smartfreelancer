import api from "../../api/api";

const state = () => ({
  current: {
    name: "",
    description: "",
  },
});

const getters = {};

const actions = {
  async fetchClient({ commit }, clientId) {
    const client = await api.getClient(clientId);
    commit("setClient", client);
  },
  async updateName({ commit }, name) {
    commit("updateName", name);
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
  updateName(state, name) {
    state.current.name = name;
  },
  updateDescription(state, description) {
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
