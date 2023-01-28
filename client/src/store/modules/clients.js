import api from "../../api/api";

// initial state
const state = () => ({
  all: [],
});

const getters = {
  getClients(state) {
    return state.all;
  },
  getClientsOptions(state) {
    return state.all.map((client) => {
      return {
        id: client.id,
        label: client.name,
      };
    });
  },
};

const actions = {
  async getClients({ commit }) {
    const clients = await api.getClients();

    commit("setClients", clients);
  },

  async deleteClient({ commit, state }, id) {
    await api.deleteClient(id);

    let clients = JSON.parse(JSON.stringify(state.all));
    clients = clients.filter((client) => client.id !== id);
    commit("setClients", clients);
  },

  async updateClient({ commit, state }, updatedClient) {
    await api.updateClient(updatedClient);
    let clients = JSON.parse(JSON.stringify(state.all));
    clients = clients.map((client) => {
      if (client.id === updatedClient.id) {
        return updatedClient;
      }

      return client;
    });
    commit("setClients", clients);
  },

  async createClient({ commit, state }, client) {
    await api.createClient(client);

    let clients = JSON.parse(JSON.stringify(state.all));
    clients.push(client);
    commit("setClients", clients);
  },
};

const mutations = {
  setClients(state, clients) {
    state.all = clients;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
