import api from "../../api/api";

// initial state
const state = () => ({
  all: [],
});

const getters = {
  getTaskById: (state) => (id) => {
    return state.all.filter((task) => task.id === id).pop();
  },
};

const actions = {
  async getTasks({ commit }) {
    const tasks = await api.getTasks();

    commit("setTasks", tasks);
  },

  async deleteTask({ commit, state, rootState, rootGetters }, id) {
    await api.deleteTask(id);

    let tasks = JSON.parse(JSON.stringify(state.all));
    tasks = tasks.filter((task) => task.id !== id);
    commit("setTasks", tasks);
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (timer.taskId === id) {
      timer.taskId = null;
      commit("timer/setTimer", timer, { root: true });
    }
  },

  async updateTask({ commit, state }, taskToUpdate) {
    const updatedTask = await api.updateTask(taskToUpdate);
    let tasks = JSON.parse(JSON.stringify(state.all));
    tasks = tasks.map((task) => {
      if (task.id === taskToUpdate.id) {
        return updatedTask;
      }

      return task;
    });
    commit("setTasks", tasks);
  },

  async createTask({ commit, state }, task) {
    const createdTask = await api.createTask(task);

    let tasks = JSON.parse(JSON.stringify(state.all));
    tasks.push(createdTask);
    commit("setTasks", tasks);
  },
};

const mutations = {
  setTasks(state, tasks) {
    state.all = tasks;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};