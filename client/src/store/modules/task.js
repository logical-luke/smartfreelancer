const emptyTask = {
  name: "",
  description: "",
  projectId: null,
};

const state = () => ({
  current: JSON.parse(JSON.stringify(emptyTask)),
});

const actions = {
  setTask({ commit }, task) {
    commit("setTask", task);
  },
  setName({ commit }, name) {
    commit("setName", name);
  },
  setProjectId({ commit }, projectId) {
    commit("setProjectId", projectId);
  },
  clearTask({ commit }) {
    commit("setTask", JSON.parse(JSON.stringify(emptyTask)));
  },
};

const mutations = {
  setTask(state, task) {
    state.current = task;
  },
  setName(state, name) {
    state.current.name = name;
  },
  setProjectId(state, projectId) {
    state.current.projectId = projectId;
  },
  setDescription(state, description) {
    state.current.description = description;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
};
