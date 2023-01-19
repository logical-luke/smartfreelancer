import api from "../../api/api";

// initial state
const state = () => ({
  all: [],
});

const getters = {};

const actions = {
  async getAllProjects({ commit }) {
    const projects = await api.getProjects();
    let orderedProjects = {};
    for (const project of projects) {
      orderedProjects[project.id] = project;
    }
    commit("setProjects", orderedProjects);
  },
  async getProject({ commit }, projectId) {
      const project = await api.getProject(projectId);
      commit("setProject", project);
  },
  async deleteProject({ commit }, id) {
    await api.deleteProject(id);
    commit("deleteProject", id);
  }
};

const mutations = {
  setProjects(state, projects) {
    state.all = projects;
  },

  decrementProjectInventory(state, { id }) {
    const project = state.all.find((project) => project.id === id);
    project.inventory--;
  },

  deleteProject(state, id) {
    delete state.all[id];
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
