import api from "@/services/api";
import store from "@/store";
import cache from "@/services/cache";

export default {
  async syncTasks() {
    const tasks = await api.getTasks();

    await store.commit("tasks/setTasks", tasks);
    await cache.set("tasks", JSON.stringify(tasks));
  },
};
