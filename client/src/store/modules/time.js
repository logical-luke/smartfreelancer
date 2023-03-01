import getUTCTimestampFromLocaltime from "@/services/time/getUTCTimestampFromLocaltime";

const state = () => ({
  serverTimeOffset: null,
  serverTime: null,
});

const mutations = {
  setServerTimeOffset(state, serverTimeOffset) {
    state.serverTimeOffset = serverTimeOffset;
  },
  setServerTime(state, serverTime) {
    state.serverTime = serverTime;
  },
};

const getters = {
  getServerTime(state) {
    return state.serverTime;
  },
  getServerTimeOffset(state) {
    return state.serverTimeOffset;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};
