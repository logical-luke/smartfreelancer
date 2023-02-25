const state = () => ({
  serverTime: null,
  serverTimeSyncId: null,
});

const mutations = {
  setServerTime(state, serverTime) {
    state.serverTime = serverTime;
  },
  setServerTimeSyncId(state, serverTimeSyncId) {
    state.serverTimeSyncId = serverTimeSyncId;
  },
};

const getters = {
  getServerTime(state) {
    return state.serverTime;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};