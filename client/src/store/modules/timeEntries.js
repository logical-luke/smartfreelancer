import api from "@/api/api";

// initial state
const state = () => ({
  all: [],
});

const actions = {
  async getTimeEntries({ commit }) {
    const timeEntries = await api.getTimeEntries();

    commit("setTimeEntries", timeEntries);
  },
};

const mutations = {
  setTimeEntries(state, timeEntries) {
    state.all = timeEntries;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
