import api from "@/api/api";

const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
};

const state = () => ({
  current: emptyTimer,
});

const actions = {
  async updateProjectId({ commit, state }, projectId) {
    if (state.current.projectId !== projectId) {
      commit("updateProjectId", projectId);
      if (state.current.id) {
        await api.updateTimer(state.current);
      }
    }
  },
};

const mutations = {
  setTimer(state, timer) {
    state.current = timer;
  },
  updateProjectId(state, projectId) {
    state.current.projectId = projectId;
  },
  clearTimer(state) {
    state.current = emptyTimer;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
};
