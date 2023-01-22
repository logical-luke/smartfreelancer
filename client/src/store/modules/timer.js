const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
  subjectName: null,
};

const state = () => ({
  current: emptyTimer,
});

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
  mutations,
};
