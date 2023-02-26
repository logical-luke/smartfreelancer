import api from "@/services/api";
import store from "@/store";
import cache from "@/services/cache";

export default {
  async syncProjects() {
    let projects = await api.getProjects();
    if (!projects) {
      projects = [];
    }
    await store.commit("projects/setProjects", projects);
    await cache.set("projects", JSON.stringify(projects));
  },
};
