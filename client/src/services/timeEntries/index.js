import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import synchronization from "@/services/synchronization";
import timer from "@/services/timer";

export default {
  async createTimeEntry(
    clientId,
    projectId,
    taskId,
    startTime,
    endTime,
    pushSync = true
  ) {
    const timeEntry = {
      id: getUuid(),
      clientId: clientId,
      projectId: projectId,
      taskId: taskId,
      startTime: startTime,
      endTime: endTime,
    };
    let timeEntries = JSON.parse(
      JSON.stringify(store.getters["timeEntries/getTimeEntries"])
    );
    timeEntries.unshift(timeEntry);
    await store.commit("timeEntries/setTimeEntries", timeEntries);
    if (pushSync) {
      synchronization.pushToQueue(
        "TimeEntry",
        "TimeEntryCreator",
        "createTimeEntry",
        timeEntry
      );
    }
  },
  async createTimeEntryFromCurrentTimer() {
    const currentTimer = await timer.getCurrentTimer();
    await this.createTimeEntry(
      currentTimer.clientId,
      currentTimer.projectId,
      currentTimer.taskId,
      currentTimer.startTime,
      currentTimer.endTime
    );
  },
};
