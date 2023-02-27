import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import cache from "@/services/cache";
import synchronization from "@/services/synchronization";
import timeEntries from "@/services/timeEntries";

export default {
  async startTimer() {
    const startTime = store.getters["time/getServerTime"];
    const newTimer = JSON.parse(
      JSON.stringify(store.getters["timer/getTimer"])
    );
    newTimer.startTime = startTime;
    newTimer.id = getUuid();

    await store.commit("timer/setTimer", newTimer);
    await cache.set("timer", JSON.stringify(newTimer));
    await synchronization.pushToQueue("Timer", "TimerCreator", "CreateTimer", newTimer);
  },
  async stopTimer() {
    const endTime = store.getters["time/getServerTime"];
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));
    if (!timer.id) {
      return;
    }
    await store.commit("timer/clearTimer");
    timer.endTime = endTime;
    await synchronization.pushToQueue("Timer", "TimerStopper", "StopTimer", timer);
    await timeEntries.createTimeEntry(
      timer.ownerId,
      timer.clientId,
      timer.projectId,
      timer.taskId,
      timer.startTime,
      timer.endTime,
      false
    );
  },
};
