import api from "@/services/api";
import cache from "@/services/cache";

const state = () => ({
  all: [],
});

const getters = {
  getClientsOptions(state) {
    return state.all.map((client) => {
      return {
        id: client.id,
        label: client.name,
      };
    });
  },
  getClientById: (state) => (id) => {
    return state.all.filter((client) => client.id === id).pop();
  },
  getClients: (state) => [...state.all],
};

const actions = {
  async updateClient({ commit, state }, clientToUpdate) {
    const updatedClient = await api.updateClient(clientToUpdate);
    let clients = JSON.parse(JSON.stringify(state.all));
    clients = clients.map((client) => {
      if (client.id === clientToUpdate.id) {
        return updatedClient;
      }

      return client;
    });
    commit("setClients", clients);
  },
};

const mutations = {
  setClients(state, clients) {
    state.all = clients;
    if (Array.isArray(clients) && clients.length > 0) {
      cache.set("clients", JSON.stringify(clients)).then();
    } else {
      cache.remove("clients").then();
    }
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
