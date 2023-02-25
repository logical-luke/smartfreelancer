import api from "@/services/api";
import store from "@/store";

export default {
  async syncProjects() {
    const projects = await api.getProjects();

    await store.commit("projects/setProjects", projects);
  },
};
