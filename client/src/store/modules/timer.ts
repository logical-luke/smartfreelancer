const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
  clientId: null,
  taskId: null,
  description: "",
};

const state = () => ({
  current: JSON.parse(JSON.stringify(emptyTimer)),
  timerMode: "timer",
});

const actions = {
  async setProjectId({ commit, state }, projectId) {
    if (state.current.projectId !== projectId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.projectId = projectId;
      commit("setTimer", timer);
    }
  },
  async setTaskId({ commit, state }, taskId) {
    if (state.current.taskId !== taskId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.taskId = taskId;
      commit("setTimer", timer);
    }
  },
  async setClientId({ commit, state }, clientId) {
    if (state.current.clientId !== clientId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.clientId = clientId;
      commit("setTimer", timer);
    }
  },
  async setDescription({ commit, state }, description) {
    if (state.current.description !== description) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.description = description;
      commit("setTimer", timer);
    }
  },
  async setStartTime({ commit, state }, startTime) {
    if (state.current.startTime !== startTime) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.startTime = startTime;
      commit("setTimer", timer);
    }
  },
  async setEndTime({ commit, state }, endTime) {
    if (state.current.endTime !== endTime) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.endTime = endTime;
      commit("setTimer", timer);
    }
  },
};

const getters = {
  getTimer: (state) => state.current,
  getTimerMode: (state) => state.timerMode,
};

const mutations = {
  setTimer(state, timer) {
    state.current = timer;
  },
  clearTimer(state) {
    state.current = JSON.parse(JSON.stringify(emptyTimer));
  },
  setTimerMode: (state, mode) => (state.timerMode = mode),
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};