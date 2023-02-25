import store from "@/store";
import syncClients from "@/services/synchronization/clients";
import syncProjects from "@/services/synchronization/projects";
import syncTasks from "@/services/synchronization/tasks";
import syncTimer from "@/services/synchronization/timer";
import api from "@/services/api";
import authorization from "@/services/authorization";
import router from "@/router";
export default {
  async syncUser() {
    let user = null;

    try {
      user = await api.getUser();
      await store.commit("setUser", user);
    } catch (err) {
      await authorization.logout();

      return router.push("/login");
    }

    if (!user) {
      await authorization.logout();

      return router.push("/login");
    }
  },
  async syncInitial() {
    const clientsFetched = syncClients();
    const projectsFetched = syncProjects();
    const tasksFetched = syncTasks();
    const timerFetched = syncTimer();

    return Promise.all([
      clientsFetched,
      projectsFetched,
      tasksFetched,
      timerFetched,
    ]).then(() => {
      store.commit("synchronization/setSynchronizationTime", new Date());
    });
  },
  async upload() {

  },
  async enableBackgroundUpload() {
    const backgroundUploadId = setInterval(() => {
      this.upload();
    }, 1000);
    store.commit("synchronization/setBackgroundUploadIntervalId", backgroundUploadId);
  },
  async disableBackgroundUpload() {
    clearInterval(store.state.synchronization.backgroundUploadIntervalId);
  },
};
