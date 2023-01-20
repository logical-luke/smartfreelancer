import api from "../../api/api";

const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
};

const state = () => ({
  current: emptyTimer,
});

const getters = {

};

const actions = {
  async fetchTimer({ commit }) {
    const project = await api.getTimer();
    commit("setTimer", project);
  }
};

const mutations = {
  setTimer(state, timer) {
    state.current = timer;
  },
  clearTimer(state) {
    state.current = emptyTimer;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
