import api from "@/services/api";
import store from "@/store";

export default async function syncTasks() {
  const tasks = await api.getTasks();

  await store.commit("tasks/setTasks", tasks);
}
