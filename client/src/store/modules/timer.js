import api from "@/services/api/api";

const emptyTimer = {
  id: null,
  startTime: null,
  projectId: null,
  clientId: null,
  taskId: null,
};

const state = () => ({
  current: JSON.parse(JSON.stringify(emptyTimer)),
});

const actions = {
  async startTimer({ commit, state, rootGetters }) {
    const newTimer = JSON.parse(JSON.stringify(state.current));
    newTimer.startTime = rootGetters["getServerTime"];
    const timer = await api.createTimer(newTimer);

    commit("setTimer", timer);
  },
  async stopTimer({ state, rootGetters, commit }) {
    const timer = JSON.parse(JSON.stringify(state.current));
    if (!timer.id) {
      return;
    }
    timer.endTime = rootGetters.getServerTime;
    const timeEntry = await api.stopTimer(timer);

    let timeEntries = JSON.parse(
      JSON.stringify(rootGetters["timeEntries/getTimeEntries"])
    );
    timeEntries.unshift(timeEntry);
    commit("timeEntries/setTimeEntries", timeEntries, { root: true });

    commit("setTimer", JSON.parse(JSON.stringify(emptyTimer)));
  },
  async getTimer({ commit, dispatch }) {
    const timer = await api.getTimer();
    if (timer && !timer.id) {
      await dispatch("clearTimer");
    }
    if (timer && timer.id) {
      commit("setTimer", timer);
    }
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
  async setTaskId({ commit, state }, taskId) {
    if (state.current.taskId !== taskId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.taskId = taskId;
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
  async clearTimer({ commit }) {
    commit("setTimer", JSON.parse(JSON.stringify(emptyTimer)));
  },
};

const getters = {
  getTimer: (state) => state.current,
};

const mutations = {
  setTimer(state, timer) {
    state.current = timer;
    localStorage.setItem("timer", JSON.stringify(timer));
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
