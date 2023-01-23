const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
};

const state = () => ({
  current: emptyTimer,
});

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
  mutations,
};
