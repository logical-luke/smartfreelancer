import api from "../../api/api";

const state = () => ({
  current: {},
});

const getters = {};

const actions = {
  async getProject({ commit }, projectId) {
    const project = await api.getProject(projectId);
    commit("setProject", project);
  },
  async updateName({ commit }, name) {
    commit("updateName", name);
  }
};

const mutations = {
  setProject(state, project) {
    state.current = project;
  },
  updateName(state, name) {
    state.current.name = name;
  },
  updateDescription(state, description) {
    state.current.description = description;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
