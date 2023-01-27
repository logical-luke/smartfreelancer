import api from "../../api/api";

const emptyProject = {
  name: "",
  description: "",
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
  setName({ commit }, name) {
    commit("setName", name);
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
