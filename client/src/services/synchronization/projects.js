import api from "@/services/api";
import store from "@/store";
import cache from "@/services/cache";

export default {
  async syncProjects() {
    const projects = await api.getProjects();

    await store.commit("projects/setProjects", projects);
    await cache.set("projects", JSON.stringify(projects));
  },
};
