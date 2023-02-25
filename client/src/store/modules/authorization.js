const state = () => ({
  token: null,
  refreshToken: null,
  authorized: false,
});

const mutations = {
  setToken(state, token) {
    state.token = token;
  },
  setRefreshToken(state, refreshToken) {
    state.refreshToken = refreshToken;
  },
  setAuthorized(state, authorized) {
    state.authorized = authorized;
  },
};

const getters = {
  isAuthorized(state) {
    return state.authorized;
  },
  getToken(state) {
    return state.token;
  },
  getRefreshToken(state) {
    return state.refreshToken;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  getters,
};
