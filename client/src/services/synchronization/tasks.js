import api from "@/services/api";
import store from "@/store";
import cache from "@/services/cache";

export default {
  async syncTasks() {
    let tasks = await api.getTasks();
    if (!tasks) {
      tasks = [];
    }
    await store.commit("tasks/setTasks", tasks);
    await cache.set("tasks", JSON.stringify(tasks));
  },
};
