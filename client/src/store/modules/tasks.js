import api from "@/services/api";
import cache from "@/services/cache";

const state = () => ({
  all: [],
});

const getters = {
  getTaskById: (state) => (id) => {
    return state.all.filter((task) => task.id === id).pop();
  },
};

const actions = {
  async deleteTask({ commit, state, rootGetters }, id) {
    await api.deleteTask(id);

    let tasks = JSON.parse(JSON.stringify(state.all));
    tasks = tasks.filter((task) => task.id !== id);
    commit("setTasks", tasks);
    await cache.set("tasks", JSON.stringify(tasks));
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (timer.taskId === id) {
      timer.taskId = null;
      commit("timer/setTimer", timer, { root: true });
      await cache.set("timer", JSON.stringify(timer));
    }
  },

  async deleteTasks({ commit, state, rootGetters }, ids) {
    await api.deleteTasks(ids);

    let tasks = JSON.parse(JSON.stringify(state.all));
    tasks = tasks.filter((task) => !ids.includes(task.id));
    commit("setTasks", tasks);
    await cache.set("tasks", JSON.stringify(tasks));
    const timer = JSON.parse(JSON.stringify(rootGetters["timer/getTimer"]));
    if (ids.includes(timer.taskId)) {
      timer.taskId = null;
      commit("timer/setTimer", timer, { root: true });
      await cache.set("timer", JSON.stringify(timer));
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
    await cache.set("tasks", JSON.stringify(tasks));
  },

  async createTask({ commit, state }, task) {
    const createdTask = await api.createTask(task);

    let tasks = JSON.parse(JSON.stringify(state.all));
    tasks.unshift(createdTask);
    commit("setTasks", tasks);
    await cache.set("tasks", JSON.stringify(tasks));
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
