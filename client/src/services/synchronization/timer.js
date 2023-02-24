import api from "@/services/api";
import store from "@/store";

export default async function syncTimer() {
  const timer = await api.getTimer();

  if (!timer.id) {
    return store.commit("timer/clearTimer");
  }

  await store.commit("timer/setTimer", timer);
}
