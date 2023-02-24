import api from "../../services/api";

// initial state
const state = () => ({
  all: [],
});

const getters = {
  getProjectById: (state) => (id) => {
    return state.all.filter((project) => project.id === id).pop();
  },
  getProjectsOptions(state) {
    return state.all.map((project) => {
      return {
        id: project.id,
        label: project.name,
      };
    });
  },
};

const actions = {
  async getProjects({ commit }) {
    const projects = await api.getProjects();

    commit("setProjects", projects);
  },

  async deleteProject({ commit, state, rootGetters }, id) {
    await api.deleteProject(id);

    let projects = JSON.parse(JSON.stringify(state.all));
    projects = projects.filter((project) => project.id !== id);
    commit("setProjects", projects);
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (timer.projectId === id) {
      timer.projectId = null;
      commit("timer/setTimer", timer, { root: true });
    }
  },

  async deleteProjects({ commit, state, rootGetters }, ids) {
    await api.deleteProjects(ids);

    let projects = JSON.parse(JSON.stringify(state.all));
    projects = projects.filter((project) => !ids.includes(project.id));
    commit("setProjects", projects);
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (ids.includes(timer.projectId)) {
      timer.projectId = null;
      commit("timer/setTimer", timer, { root: true });
    }
  },

  async updateProject({ commit, state }, projectToUpdate) {
    const updatedProject = await api.updateProject(projectToUpdate);
    let projects = JSON.parse(JSON.stringify(state.all));
    projects = projects.map((project) => {
      if (project.id === projectToUpdate.id) {
        return updatedProject;
      }

      return project;
    });
    commit("setProjects", projects);
  },

  async createProject({ commit, state }, project) {
    const createdProject = await api.createProject(project);

    let projects = JSON.parse(JSON.stringify(state.all));
    projects.unshift(createdProject);
    commit("setProjects", projects);
  },
};

const mutations = {
  setProjects(state, projects) {
    state.all = projects;
    localStorage.setItem("projects", JSON.stringify(projects));
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
