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
};

const actions = {
  async deleteClient({ commit, state, rootGetters }, id) {
    await api.deleteClient(id);

    let clients = JSON.parse(JSON.stringify(state.all));
    clients = clients.filter((client) => client.id !== id);
    commit("setClients", clients);
    await cache.set("clients", JSON.stringify(clients));
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (timer.clientId === id) {
      timer.clientId = null;
      commit("timer/setTimer", timer, { root: true });
      await cache.set("timer", JSON.stringify(timer));
    }
  },

  async deleteClients({ commit, state, rootGetters }, ids) {
    await api.deleteClients(ids);

    let clients = JSON.parse(JSON.stringify(state.all));
    clients = clients.filter((client) => !ids.includes(client.id));
    commit("setClients", clients);
    await cache.set("clients", JSON.stringify(clients));
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (ids.includes(timer.clientId)) {
      timer.clientId = null;
      commit("timer/setTimer", timer, { root: true });
      await cache.set("timer", JSON.stringify(timer));
    }
  },

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
    await cache.set("clients", JSON.stringify(clients));
  },

  async createClient({ commit, state }, newClient) {
    const client = await api.createClient(newClient);
    let clients = JSON.parse(JSON.stringify(state.all));
    clients.unshift(client);
    commit("setClients", clients);
    await cache.set("clients", JSON.stringify(clients));
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
