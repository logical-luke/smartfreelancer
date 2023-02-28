import getUTCTimestampFromLocaltime from "@/services/time/getUTCTimestampFromLocaltime";

const state = () => ({
  serverTimeOffset: null,
});

const mutations = {
  setServerTimeOffset(state, serverTimeOffset) {
    state.serverTimeOffset = serverTimeOffset;
  },
};

const getters = {
  getServerTime(state) {
    return getUTCTimestampFromLocaltime() + state.serverTimeOffset;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};
