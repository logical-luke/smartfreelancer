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

  async deleteProject({ commit, state }, id) {
    await api.deleteProject(id);

    let projects = JSON.parse(JSON.stringify(state.all));
    projects = projects.filter((project) => project.id !== id);
    commit("setProjects", projects);
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

  async createProject({ commit, state }, project) {
    await api.createProject(project);

    let projects = JSON.parse(JSON.stringify(state.all));
    projects.push(project);
    commit("setProjects", projects);
  },
};

const mutations = {
  setProjects(state, projects) {
    state.all = projects;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
