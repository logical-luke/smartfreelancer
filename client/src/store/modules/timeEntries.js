const state = () => ({
  all: [],
});

const getters = {
  getTimeEntries: (state) => state.all,
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
  getters,
};
