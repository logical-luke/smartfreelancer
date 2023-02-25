import api from "@/services/api";
import store from "@/store";

export default {
  async syncTimer() {
    const timer = await api.getTimer();

    if (!timer.id) {
      return store.commit("timer/clearTimer");
    }

    await store.commit("timer/setTimer", timer);
  },
  async startTimer(timer) {
    await api.createTimer(timer);
  },
  async stopTimer(timer) {
    await api.stopTimer(timer);
  },
};
