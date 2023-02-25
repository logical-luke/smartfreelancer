import api from "@/services/api";
import store from "@/store";

export default {
  async syncTasks() {
    const tasks = await api.getTasks();

    await store.commit("tasks/setTasks", tasks);
  },
};
