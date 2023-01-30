import api from "@/api/api";

const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
  clientId: null,
};

const state = () => ({
  current: JSON.parse(JSON.stringify(emptyTimer)),
});

const actions = {
  async startTimer({ commit, state }) {
    const timer = await api.createTimer(state.current);

    commit("setTimer", timer);
  },
  async stopTimer({ commit }) {
    await api.stopTimer();

    commit("setTimer", JSON.parse(JSON.stringify(emptyTimer)));
  },
  async setProjectId({ commit, state }, projectId) {
    if (state.current.projectId !== projectId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.projectId = projectId;
      timer.clientId = null;
      commit("setTimer", timer);
      if (state.current.id) {
        await api.updateTimer(state.current);
      }
    }
  },
  async setClientId({ commit, state }, clientId) {
    if (state.current.clientId !== clientId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.clientId = clientId;
      timer.projectId = null;
      commit("setTimer", timer);
      if (state.current.id) {
        await api.updateTimer(state.current);
      }
    }
  },
};

const getters = {
  getTimer: (state) => state.current,
};

const mutations = {
  setTimer(state, timer) {
    state.current = timer;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
