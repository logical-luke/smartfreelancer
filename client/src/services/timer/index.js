import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import cache from "@/services/cache";
import synchronization from "@/services/synchronization";
import timeEntries from "@/services/timeEntries";
import getSecondsTimestampFromDate from "../time/getSecondsTimestampFromDate";
import getRelativeTime from "@/services/time/relativeTimeGetter";

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
    async setProject(id) {
        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        if (timer.projectId === id) {
            return;
        }

        await store.dispatch("timer/setClientId", null);
        await store.dispatch("timer/setProjectId", id);
        await store.dispatch("timer/setTaskId", null);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
    },
    async setClient(id) {
        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        if (timer.clientId === id) {
            return;
        }

        await store.dispatch("timer/setClientId", id);
        await store.dispatch("timer/setProjectId", null);
        await store.dispatch("timer/setTaskId", null);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
    },
    async setTask(id) {
        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        if (timer.taskId === id) {
            return;
        }

        await store.dispatch("timer/setClientId", null);
        await store.dispatch("timer/setProjectId", null);
        await store.dispatch("timer/setTaskId", id);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
    },
    async setEmptySubject() {
        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        await store.dispatch("timer/setClientId", null);
        await store.dispatch("timer/setProjectId", null);
        await store.dispatch("timer/setTaskId", null);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
    },
    async setDescription(description) {
        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        if (timer.description === description) {
            return;
        }

        await store.dispatch("timer/setDescription", description);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
    },
    async setStartTime(startTime) {
        const startTimeTimestamp = getSecondsTimestampFromDate(startTime);

        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        if (timer.startTime === startTimeTimestamp) {
            return;
        }

        await store.dispatch("timer/setStartTime", startTimeTimestamp);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
    },
    async setEndTime(endTime) {
        const endTimeTimestamp = getSecondsTimestampFromDate(endTime);

        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        if (timer.endTime === endTimeTimestamp) {
            return;
        }

        await store.dispatch("timer/setEndTime", endTimeTimestamp);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
    },
    async setDuration(duration) {
        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

        const adjustedStartTime = this.endTime ?
            timer.endTime - duration : store.getters["time/getServerTime"] - duration;

        if (timer.startTime === adjustedStartTime) {
            return;
        }

        await store.dispatch("timer/setStartTime", adjustedStartTime);

        await synchronization.pushToQueue("Timer", "TimerUpdater", "UpdateTimer", timer);
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
        if (await this.isEndTimeManuallySet()) {
            const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));

            if (timer.endTime) {
                return timer.endTime;
            }
        }

        return store.getters["time/getServerTime"];
    },
    async getTimerDurations() {
        let time = [];

        if (await this.isTimerRunning()) {
            const {
                hours,
                minutes,
                seconds
            } = getRelativeTime(await this.getStartTime(), store.getters["time/getServerTime"]);
            time.hours = hours;
            time.minutes = minutes;
            time.seconds = seconds;
        } else {
            time.hours = "00";
            time.minutes = "00";
            time.seconds = "00";
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
    },
    async isCurrentRunningTimer(taskId, projectId, clientId, global) {
        const timer = JSON.parse(JSON.stringify(store.getters["timer/getTimer"]));
        return (projectId && timer.projectId === projectId)
            || (taskId && timer.taskId === taskId)
            || (clientId && timer.clientId === clientId)
            || (timer.id && global);
    },
    async isEndTimeManuallySet() {
        return store.getters["timer/isEndTimeManuallySet"];
    },
    async markEndTimeAsManuallySet() {
        store.commit("timer/setEndTimeManuallySet", true);
        await cache.set("endTimeManuallySet", true);
    },
    async toggleEndTimeManuallySet() {
        const currentEndTimeManuallySet = await store.getters["timer/isEndTimeManuallySet"];
        const newEndTimeManuallySet = !currentEndTimeManuallySet;
        store.commit("timer/setEndTimeManuallySet", newEndTimeManuallySet);
        await cache.set("endTimeManuallySet", newEndTimeManuallySet);
    }
};
