import getServerTime from "@/services/time/serverTimeGetter";
import store from "@/store";

export default {
  async enableServerTimeSync() {
    if (!store.getters["time/isServerTimeSyncEnabled"]) {
      const serverTime = await getServerTime();
      await store.commit("time/setServerTime", serverTime);
      const serverTimeSyncId = setInterval(() => {
        if (store.getters["time/getServerTime"]) {
          store.commit(
            "time/setServerTime",
            Number(store.getters["time/getServerTime"]) + 1
          );
        }
      }, 1000);
      store.commit("time/setServerTimeSyncId", serverTimeSyncId);
    }
  },
  async disableServerTimeSync() {
    if (store.getters["time/getServerTimeSyncId"]) {
      clearInterval(store.getters["time/getServerTimeSyncId"]);
      await store.commit("time/setServerTimeSyncId", null);
    }
  },
};
