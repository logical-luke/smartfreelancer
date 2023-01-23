import api from "../../api/api";

// initial state
const state = () => ({
  all: [],
});

const getters = {};

const actions = {
  async fetchAllClients({ commit }) {
    const clients = await api.getClients();
    let orderedClients = {};
    for (const client of clients) {
      orderedClients[client.id] = client;
    }
    commit("setClients", orderedClients);
  },
  async fetchClient({ commit }, clientId) {
      const client = await api.getClient(clientId);
      commit("setClient", client);
  },
  async deleteClient({ commit }, id) {
    await api.deleteClient(id);
    commit("deleteClient", id);
  }
};

const mutations = {
  setClients(state, clients) {
    state.all = clients;
  },


  deleteClient(state, id) {
    delete state.all[id];
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
