import api from "@/services/api";
import store from "@/store";
import cache from "@/services/cache";

export default {
  async syncTimer() {
    const timer = await api.getTimer();

    if (!timer.id) {
      await store.commit("timer/clearTimer");
      const timer = await store.getters["timer/getTimer"];
      await cache.set("timer", JSON.stringify(timer));

      return;
    }

    await store.commit("timer/setTimer", timer);
    await cache.set("timer", JSON.stringify(timer));
  },
  async startTimer(timer) {
    await api.createTimer(timer);
  },
  async stopTimer(timer) {
    await api.stopTimer(timer);
  },
};
