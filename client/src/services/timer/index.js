import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import cache from "@/services/cache";
import timeEntries from "@/services/timeEntries";
import getSecondsTimestampFromDate from "../time/getSecondsTimestampFromDate";
import getRelativeTime from "@/services/time/relativeTimeGetter";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";

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
  },
  async stopTimer() {
    const endTime = store.getters["time/getServerTime"];
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));
    if (!timer.id) {
      return;
    }
    await store.commit("timer/clearTimer");
    timer.endTime = endTime;
    await timeEntries.createTimeEntry(
      timer.clientId,
      timer.projectId,
      timer.taskId,
      timer.startTime,
      timer.endTime,
      false
    );
  },
  async setProject(id) {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    if (timer.projectId === id) {
      return;
    }

    await store.dispatch("timer/setClientId", null);
    await store.dispatch("timer/setProjectId", id);
    await store.dispatch("timer/setTaskId", null);
  },
  async setClient(id) {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    if (timer.clientId === id) {
      return;
    }

    await store.dispatch("timer/setClientId", id);
    await store.dispatch("timer/setProjectId", null);
    await store.dispatch("timer/setTaskId", null);
  },
  async setTask(id) {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    if (timer.taskId === id) {
      return;
    }

    await store.dispatch("timer/setClientId", null);
    await store.dispatch("timer/setProjectId", null);
    await store.dispatch("timer/setTaskId", id);
  },
  async setEmptySubject() {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    await store.dispatch("timer/setClientId", null);
    await store.dispatch("timer/setProjectId", null);
    await store.dispatch("timer/setTaskId", null);
  },
  async setDescription(description) {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    if (timer.description === description) {
      return;
    }

    await store.dispatch("timer/setDescription", description);
  },
  async setStartTime(startTime) {
    if (startTime instanceof Date) {
      startTime = getSecondsTimestampFromDate(startTime);
    }

    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    if (timer.startTime === startTime) {
      return;
    }

    await store.dispatch("timer/setStartTime", startTime);
  },
  async setEndTime(endTime) {
    if (endTime instanceof Date) {
      endTime = getSecondsTimestampFromDate(endTime);
    }

    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    if (timer.endTime === endTime) {
      return;
    }

    await store.dispatch("timer/setEndTime", endTime);
  },
  async setDuration(duration) {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    const adjustedStartTime = this.endTime
      ? timer.endTime - duration
      : store.getters["time/getServerTime"] - duration;

    if (timer.startTime === adjustedStartTime) {
      return;
    }

    await store.dispatch("timer/setStartTime", adjustedStartTime);
  },
  async isTimerRunning() {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    return timer && null !== timer.id;
  },
  async getStartTime() {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

    if (timer.startTime) {
      return timer.startTime;
    }

    return store.getters["time/getServerTime"];
  },
  async getEndTime() {
    if (await this.isManualMode()) {
      const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

      if (timer.endTime) {
        return timer.endTime;
      }
    }

    return store.getters["time/getServerTime"];
  },
  async getTimerDurations() {
    let time = {
      hours: "00",
      minutes: "00",
      seconds: "00",
    };

    if ((await this.isTimerRunning()) || (await this.isManualMode())) {
      const { hours, minutes, seconds } = getRelativeTime(
        await this.getStartTime(),
        await this.getEndTime()
      );
      time.hours = hours;
      time.minutes = minutes;
      time.seconds = seconds;
    }

    return time;
  },
  async isManualMode() {
    return store.getters["timer/getTimerMode"] === "manual";
  },
  async isTimerMode() {
    return store.getters["timer/getTimerMode"] === "timer";
  },
  async toggleTimerMode() {
    const currentTimerMode = await store.getters["timer/getTimerMode"];
    const newTimerMode = currentTimerMode === "timer" ? "manual" : "timer";
    store.commit("timer/setTimerMode", newTimerMode);
    await cache.set("timerMode", newTimerMode);
    if (newTimerMode === "manual") {
      await this.setNewManualTimerStartEndTime();
    } else {
      await this.clearTimer();
    }
  },
  async setNewManualTimerStartEndTime() {
    const secondsInFifteenMinutes = 15 * 60;
    await this.setStartTime(
      store.getters["time/getServerTime"] - secondsInFifteenMinutes
    );
    await this.setEndTime(store.getters["time/getServerTime"]);
  },
  async clearTimer() {
    await store.commit("timer/clearTimer");
  },
  async isCurrentRunningTimer(taskId, projectId, clientId, global) {
    const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));
    return (
      (projectId && timer.projectId === projectId) ||
      (taskId && timer.taskId === taskId) ||
      (clientId && timer.clientId === clientId) ||
      (timer.id && global)
    );
  },
  async adjustDays(startDay, endDay) {
    const timerStartTime = getDateFromSecondsTimestamp(
      await this.getStartTime()
    );

    if (!(startDay instanceof Date)) {
      startDay = new Date(startDay);
    }

    if (timerStartTime.getDate() !== startDay.getDate()) {
      timerStartTime.setDate(startDay.getDate());
    }

    if (timerStartTime.getMonth() !== startDay.getMonth()) {
      timerStartTime.setMonth(startDay.getMonth());
    }

    if (timerStartTime.getFullYear() !== startDay.getFullYear()) {
      timerStartTime.setFullYear(startDay.getFullYear());
    }

    await this.setStartTime(timerStartTime);

    if (await this.isManualMode()) {
      if (!endDay) {
        endDay = startDay;
      }

      const timerEndTime = getDateFromSecondsTimestamp(await this.getEndTime());

      if (endDay instanceof Date) {
        endDay = new Date(endDay);
      }

      if (timerEndTime.getDate() !== endDay.getDate()) {
        timerEndTime.setDate(endDay.getDate());
      }

      if (timerEndTime.getMonth() !== endDay.getMonth()) {
        timerEndTime.setMonth(endDay.getMonth());
      }

      if (timerEndTime.getFullYear() !== endDay.getFullYear()) {
        timerEndTime.setFullYear(endDay.getFullYear());
      }

      await this.setEndTime(timerEndTime);
    }
  },
  async getCurrentTimer() {
    return JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));
  },
};
