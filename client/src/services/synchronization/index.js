import store from "@/store";
import authorization from "@/services/authorization";
import api from "@/services/api";
import router from "@/router";
import getUuid from "@/services/uuidGenerator";
import clients from "@/services/synchronization/clients";
import projects from "@/services/synchronization/projects";
import tasks from "@/services/synchronization/tasks";
import timer from "@/services/synchronization/timer";
import timeEntries from "@/services/synchronization/timeEntries";

const queues = {
  clients: clients,
  projects: projects,
  tasks: tasks,
  timer: timer,
  timeEntries: timeEntries,
};

const milliseconds_in_second = 1000;
const milliseconds_in_minute = milliseconds_in_second * 60;

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
  async syncAll() {
    if (
      store.getters["synchronization/isOffline"] ||
      store.getters["synchronization/isBackgroundUploadInProgress"] ||
      store.getters["synchronization/isBackgroundDownloadInProgress"]
    ) {
      return;
    }
    await store.commit("synchronization/setBackgroundDownloadInProgress", true);
    const clientsFetched = await clients.syncClients();
    const projectsFetched = await projects.syncProjects();
    const tasksFetched = await tasks.syncTasks();
    const timerFetched = await timer.syncTimer();

    return Promise.all([
      clientsFetched,
      projectsFetched,
      tasksFetched,
      timerFetched,
    ]).then(() => {
      store.commit("synchronization/setSynchronizationTime", new Date());
      store.commit("synchronization/setBackgroundDownloadInProgress", false);
    });
  },
  async uploadQueue() {
    if (
      store.getters["synchronization/isOffline"] ||
      store.getters["synchronization/isBackgroundUploadInProgress"] ||
      store.getters["synchronization/isBackgroundDownloadInProgress"]
    ) {
      return;
    }

    await store.commit("synchronization/setBackgroundUploadInProgress", true);

    const queue = await store.getters["synchronization/getQueue"];
    for (let i = 0; i < queue.length; i++) {
      const queueItem = queue[i];
      if (!(queueItem.queue in queues)) {
        continue;
      }
      const queueSyncer = queues[queueItem.queue];
      if (!(queueItem.action in queueSyncer)) {
        continue;
      }
      try {
        await queueSyncer[queueItem.action](queueItem.payload);
        await store.dispatch("synchronization/removeFromQueue", queueItem);
      } catch (e) {
        await store.commit("synchronization/setSynchronizationFailed", true);
        await this.disableBackgroundUpload();
        await this.disableBackgroundSync();
      }
    }

    await store.commit("synchronization/setSynchronizationTime", new Date());
    await store.commit("synchronization/setBackgroundUploadInProgress", false);
  },
  async enableBackgroundSync() {
    const backgroundDownloadId = setInterval(() => {
      this.syncAll();
    }, milliseconds_in_minute);
    await store.commit(
      "synchronization/setBackgroundDownloadId",
      backgroundDownloadId
    );
  },
  async disableBackgroundSync() {
    clearInterval(store.state.synchronization.backgroundDownloadIntervalId);
  },
  async enableBackgroundUpload() {
    const backgroundUploadId = setInterval(() => {
      this.uploadQueue();
    }, milliseconds_in_second);
    store.commit(
      "synchronization/setBackgroundUploadIntervalId",
      backgroundUploadId
    );
  },
  async disableBackgroundUpload() {
    clearInterval(store.state.synchronization.backgroundUploadIntervalId);
  },
  async pushToQueue(queue, action, payload) {
    await store.dispatch("synchronization/pushToQueue", {
      id: getUuid(),
      queue: queue,
      action: action,
      payload: payload,
    });
  },
};
