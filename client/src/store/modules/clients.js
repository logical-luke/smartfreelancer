import api from "@/services/api";

const state = () => ({
  clients: [],
});

const actions = {
  async load({ commit }) {
    const clients = await api.getClients();

    commit("setClients", clients);
  },
  async create({ commit, state }, client) {
    const newClient = await api.createClient(client);

    commit("setClients", [...state.clients, newClient]);
  },
  async delete({ commit, state }, id) {
    await api.deleteClient(id);

    commit(
      "setClients",
      state.clients.filter((client) => client.id !== id)
    );
  },
  async update({ commit, state }, client) {
    console.log(client);
    const updatedClient = await api.updateClient(client);

    commit(
      "setClients",
      state.clients.map((c) => (c.id === updatedClient.id ? updatedClient : c))
    );
  },
};

const getters = {
  getClients: (state) => [...state.clients],
  getClient: (state) => (clientId) =>
    JSON.parse(
      JSON.stringify(state.clients.find((client) => client.id === clientId))
    ),
};

const mutations = {
  setClients(state, clients) {
    state.clients = clients;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
