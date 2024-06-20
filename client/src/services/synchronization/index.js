import store from "@/store";
import authorization from "@/services/authorization";
import api from "@/services/api";
import router from "@/router";
import getUuid from "@/services/uuidGenerator";
import getUTCTimestampFromLocaltime from "@/services/time/getUTCTimestampFromLocaltime";

const millisecondsInSecond = 1000;
const millisecondsInMinute = millisecondsInSecond * 60;

export default {
  async syncUser() {
    let user = null;
    let time = null;
    try {
      const requestTime = Math.round(Date.now() / millisecondsInSecond);
      const response = await api.getUser();
      const responseTime = Math.round(Date.now() / millisecondsInSecond);

      user = response.user;
      time = response.time;

      const returnTime = responseTime - requestTime;
      const serverTime = time - returnTime;

      const offset = serverTime - getUTCTimestampFromLocaltime();

      await store.commit("time/setServerTimeOffset", offset);
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
      store.getters["synchronization/isBackgroundDownloadInProgress"] ||
      !store.getters["synchronization/isQueueEmpty"]
    ) {
      return;
    }
    await store.commit("synchronization/setBackgroundDownloadInProgress", true);

    const initial = await api.getInitial();

    let { clients, projects, tasks, timer } = initial;

    if (Array.isArray(clients) && clients.length > 0) {
      await store.commit("clients/setClients", clients);
    }
    if (Array.isArray(projects) && projects.length > 0) {
      await store.commit("projects/setProjects", projects);
    }
    if (Array.isArray(tasks) && tasks.length > 0) {
      await store.commit("tasks/setTasks", tasks);
    }

    if (timer && timer.id) {
      await store.commit("timer/setTimer", timer);
    } else {
      await store.commit("timer/clearTimer");
    }

    await store.commit("synchronization/setSynchronizationTime", new Date());
    await store.commit(
      "synchronization/setBackgroundDownloadInProgress",
      false
    );
  },
  async uploadQueue() {
    const queue = await store.getters["synchronization/getQueue"];
    if (
      store.getters["synchronization/isOffline"] ||
      store.getters["synchronization/isBackgroundUploadInProgress"] ||
      store.getters["synchronization/isBackgroundDownloadInProgress"] ||
      queue.length < 1
    ) {
      return;
    }

    await store.commit("synchronization/setBackgroundUploadInProgress", true);

    for (let i = 0; i < queue.length; i++) {
      const queueItem = queue[i];
      try {
        await api.pushSyncItem(queueItem);
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
    if (!store.getters["synchronization/isBackgroundSyncEnabled"]) {
      const backgroundDownloadId = setInterval(() => {
        this.syncAll();
      }, millisecondsInMinute);
      await store.commit(
        "synchronization/setBackgroundDownloadIntervalId",
        backgroundDownloadId
      );
    }
  },
  async disableBackgroundSync() {
    if (store.getters["synchronization/isBackgroundSyncEnabled"]) {
      clearInterval(store.state.synchronization.backgroundDownloadIntervalId);
    }
  },
  async enableBackgroundUpload() {
    if (!store.getters["synchronization/isBackgroundUploadEnabled"]) {
      const backgroundUploadId = setInterval(() => {
        this.uploadQueue();
      }, millisecondsInSecond * 2);
      store.commit(
        "synchronization/setBackgroundUploadIntervalId",
        backgroundUploadId
      );
    }
  },
  async disableBackgroundUpload() {
    if (store.getters["synchronization/isBackgroundUploadEnabled"]) {
      clearInterval(store.state.synchronization.backgroundUploadIntervalId);
    }
  },
  async pushToQueue(resource, action, data) {
    await store.dispatch("synchronization/pushToQueue", {
      id: getUuid(),
      resource: resource,
      action: action,
      data: data,
    });
  },
};
