import api from "@/services/api";
import getUuid from "@/services/uuidGenerator";
import cache from "@/services/cache";

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
  async setProjectId({ commit, state }, projectId) {
    if (state.current.projectId !== projectId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.projectId = projectId;
      timer.clientId = null;
      commit("setTimer", timer);
    }
  },
  async setTaskId({ commit, state }, taskId) {
    if (state.current.taskId !== taskId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.taskId = taskId;
      timer.clientId = null;
      commit("setTimer", timer);
    }
  },
  async setClientId({ commit, state }, clientId) {
    if (state.current.clientId !== clientId) {
      const timer = JSON.parse(JSON.stringify(state.current));
      timer.clientId = clientId;
      timer.projectId = null;
      commit("setTimer", timer);
    }
  },
};

const getters = {
  getTimer: (state) => state.current,
};

const mutations = {
  setTimer(state, timer) {
    state.current = timer;
    cache.set("timer", JSON.stringify(timer)).then(() => {});
  },
  clearTimer(state) {
    state.current = JSON.parse(JSON.stringify(emptyTimer));
    cache.set("timer", JSON.stringify(emptyTimer)).then(() => {});
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
