import api from "../../api/api";

// initial state
const state = () => ({
  all: [],
});

const getters = {
  getProjects(state) {
    return state.all;
  },
};

const actions = {
  async getProjects({ commit }) {
    const projects = await api.getProjects();

    commit("setProjects", projects);
  },

  async deleteProject({ commit }, id) {
    await api.deleteProject(id);
    commit("deleteProject", id);
  },

  async updateProject({ commit, state }, updatedProject) {
    await api.updateProject(updatedProject);
    let projects = JSON.parse(JSON.stringify(state.all));
    projects = projects.map((project) => {
      if (project.id === updatedProject.id) {
        return updatedProject;
      }

      return project;
    });
    commit("setProjects", projects);
  },
};

const mutations = {
  setProjects(state, projects) {
    state.all = projects;
  },

  deleteProject(state, id) {
    delete state.all[id];
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
