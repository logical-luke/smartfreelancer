import api from "../../services/api";

const emptyProject = {
  name: "",
  description: "",
  clientId: null,
};

const state = () => ({
  current: JSON.parse(JSON.stringify(emptyProject)),
});

const getters = {};

const actions = {
  async getProject({ commit }, projectId) {
    const project = await api.getProject(projectId);
    commit("setProject", project);
  },
  setProject({ commit }, project) {
    commit("setProject", project);
  },
  setName({ commit }, name) {
    commit("setName", name);
  },
  setClientId({ commit }, clientId) {
    commit("setClientId", clientId);
  },
  clearProject({ commit }) {
    commit("setProject", JSON.parse(JSON.stringify(emptyProject)));
  },
};

const mutations = {
  setProject(state, project) {
    state.current = project;
  },
  setName(state, name) {
    state.current.name = name;
  },
  setClientId(state, clientId) {
    state.current.clientId = clientId;
  },
  setDescription(state, description) {
    state.current.description = description;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
