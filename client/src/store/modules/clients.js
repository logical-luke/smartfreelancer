const state = () => ({
  all: [],
});

const getters = {
  getClients: (state) => [...state.all],
};

const mutations = {
  setClients(state, clients) {
    state.all = clients;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};
