import api from "@/services/api";

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
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (timer.clientId === id) {
      timer.clientId = null;
      commit("timer/setTimer", timer, { root: true });
    }
  },

  async deleteClients({ commit, state, rootGetters }, ids) {
    await api.deleteClients(ids);

    let clients = JSON.parse(JSON.stringify(state.all));
    clients = clients.filter((client) => !ids.includes(client.id));
    commit("setClients", clients);
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (ids.includes(timer.clientId)) {
      timer.clientId = null;
      commit("timer/setTimer", timer, { root: true });
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
  },

  async createClient({ commit, state }, newClient) {
    const client = await api.createClient(newClient);
    console.log(client);
    let clients = JSON.parse(JSON.stringify(state.all));
    clients.unshift(client);
    commit("setClients", clients);
  },
};

const mutations = {
  setClients(state, clients) {
    state.all = clients;
    localStorage.setItem("clients", JSON.stringify(clients));
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
