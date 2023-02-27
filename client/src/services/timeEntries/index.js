import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import synchronization from "@/services/synchronization";

const queueName = "timeEntries";

export default {
  async createTimeEntry(
    ownerId,
    clientId,
    projectId,
    taskId,
    startTime,
    endTime,
    pushSync = true
  ) {
    const timeEntry = {
      id: getUuid(),
      ownerId: ownerId,
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
      await synchronization.pushToQueue(queueName, 'createTimeEntry', timeEntry);
    }
  },
};
