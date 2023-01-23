import api from "../../api/api";

// initial state
const state = () => ({
  all: [],
});

const getters = {
  getProjectsNamesWithIds(state) {
    return Object.keys(state.all).map((key) => {
      return {
        id: state.all[key].id,
        name: state.all[key].name
      }
    });
  }
}

const actions = {
  async fetchAllProjects({ commit }) {
    const projects = await api.getProjects();
    let orderedProjects = {};
    for (const project of projects) {
      orderedProjects[project.id] = project;
    }
    commit("setProjects", orderedProjects);
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


  deleteProject(state, id) {
    delete state.all[id];
  }
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
