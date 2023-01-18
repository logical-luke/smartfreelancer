import api from "../../api/api";

// initial state
const state = () => ({
  all: [],
});

// getters
const getters = {};

// actions
const actions = {
  async getAllProjects({ commit }) {
    const projects = await api.getProjects();
    commit("setProjects", projects);
  },
  async deleteProject({ commit }, id) {
    await api.deleteProject(id);
    commit("deleteProject", id);
  }
};

// mutations
const mutations = {
  setProjects(state, projects) {
    state.all = projects;
  },

  decrementProjectInventory(state, { id }) {
    const project = state.all.find((project) => project.id === id);
    project.inventory--;
  },

  deleteProject(state, id) {
    const index = state.all.findIndex(project => project.id === id);

    if (index > -1) {
      state.all.splice(index, 1);
    }
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
