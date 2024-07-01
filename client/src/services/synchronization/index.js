import store from "@/store";
import authorization from "@/services/authorization";
import api from "@/services/api";
import router from "@/router";
import getUuid from "@/services/uuidGenerator";
import getUTCTimestampFromLocaltime from "@/services/time/getUTCTimestampFromLocaltime";
import cache from "@/services/cache";

const millisecondsInSecond = 1000;
const millisecondsInMinute = millisecondsInSecond * 60;

export default {
  async fetchUser() {
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
      await store.commit(
          "time/setServerTime",
          getUTCTimestampFromLocaltime() + offset
      );
      setInterval(async () => {
        await store.commit(
            "time/setServerTime",
            getUTCTimestampFromLocaltime() +
            store.getters["time/getServerTimeOffset"]
        );
      }, 1000);
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
  async fetchAllData() {
    if (
      store.getters["synchronization/isOffline"] ||
      store.getters["synchronization/isBackgroundUploadInProgress"] ||
      store.getters["synchronization/isBackgroundFetchingInProgress"] ||
      !store.getters["synchronization/isQueueEmpty"]
    ) {
      return;
    }
    await store.commit("synchronization/setBackgroundFetchingInProgress", true);

    const initial = await api.getInitial();

    let { clients, projects, tasks, timer } = initial;

    if (Array.isArray(clients) && clients.length > 0) {
      await store.commit("clients/setClients", clients);
      await cache.set('clients', JSON.stringify(clients));
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
      "synchronization/setBackgroundFetchingInProgress",
      false
    );
  },
  async uploadQueue() {
    const queue = await store.getters["synchronization/getQueue"];
    if (
      store.getters["synchronization/isOffline"] ||
      store.getters["synchronization/isBackgroundUploadInProgress"] ||
      store.getters["synchronization/isBackgroundFetchingInProgress"] ||
      queue.length < 1
    ) {
      return;
    }

    await store.commit("synchronization/setBackgroundUploadInProgress", true);

    for (let i = 0; i < queue.length; i++) {
      const queueItem = queue[i];
      try {
        const synchronizationResponse = await api.pushSyncItem(queueItem);
        await this.removeFromQueue(queueItem.id);
        await this.pushToSynchronizationLogQueue(synchronizationResponse.id);
      } catch (e) {
        await store.commit("synchronization/setSynchronizationFailed", true);
        await this.disableBackgroundUpload();
        await this.disableBackgroundFetching();
      }
    }
    await store.commit("synchronization/setSynchronizationTime", new Date());

    await store.commit("synchronization/setBackgroundUploadInProgress", false);
  },
  async enableBackgroundFetching() {
    if (!store.getters["synchronization/isBackgroundFetchingEnabled"]) {
      const backgroundFetchingId = setInterval(() => {
        this.fetchAllData();
      }, millisecondsInMinute * 5);
      await store.commit(
        "synchronization/setBackgroundFetchingIntervalId",
        backgroundFetchingId
      );
    }
  },
  async disableBackgroundFetching() {
    if (store.getters["synchronization/isBackgroundFetchingEnabled"]) {
      clearInterval(store.getters["synchronization/getBackgroundFetchingIntervalId"]);
      await store.commit(
          "synchronization/setBackgroundFetchingIntervalId",
          null
      );
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
      clearInterval(store.getters["synchronization/getBackgroundUploadIntervalId"]);
      store.commit(
          "synchronization/setBackgroundUploadIntervalId",
          null
      );
    }
  },
  pushToQueue(resource, action, data) {
    const queue = JSON.parse(
        JSON.stringify(store.getters["synchronization/getQueue"])
    );
    queue.push({
      id: getUuid(),
      resource: resource,
      action: action,
      time: store.getters["time/getServerTime"],
      data: data,
    });
    store.commit("synchronization/setQueue", queue);
    cache.set("queue", JSON.stringify(queue)).then();
  },
  async removeFromQueue(queueItemId) {
    const queue = JSON.parse(
        JSON.stringify(store.getters["synchronization/getQueue"])
    );
    queue.splice(
        queue.findIndex((obj) => obj.id === queueItemId),
        1
    );
    store.commit("synchronization/setQueue", queue);
    cache.set("queue", JSON.stringify(queue)).then();
  },
  pushToSynchronizationLogQueue(synchronizationLogId) {
    const queue = JSON.parse(
        JSON.stringify(store.getters["synchronization/getSynchronizationLogQueue"])
    );
    queue.push(synchronizationLogId);
    store.commit("synchronization/setSynchronizationLogQueue", queue);
    cache.set("synchronizationLogQueue", JSON.stringify(queue)).then();
  },
};
