import api from "@/api/api";

const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
};

const state = () => ({
  current: JSON.parse(JSON.stringify(emptyTimer)),
});

const actions = {
  async startTimer({ commit, state }) {
    console.log(state.current);
    const timer = await api.createTimer(state.current);

    commit("setTimer", timer);
  },
  async stopTimer({ commit }) {
    await api.stopTimer();

    commit("setProjectId", null);
    commit("setTimer", JSON.parse(JSON.stringify(emptyTimer)));
  },
  async setProjectId({ commit, state }, projectId) {
    if (state.current.projectId !== projectId) {
      commit("setProjectId", projectId);
      if (state.current.id) {
        await api.updateTimer(state.current);
      }
    }
  },
};

const getters = {
  getTimer: (state) => state.current,
}

const mutations = {
  setTimer(state, timer) {
    state.current = timer;
  },
  setProjectId(state, projectId) {
    state.current.projectId = projectId;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
