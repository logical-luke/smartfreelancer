import getRelativeTime from "@/services/time/relativeTimeGetter";
import store from "@/store";
import getUuid from "@/services/uuidGenerator";

export default {
  async getDurations() {
    let time = {
      hours: "00",
      minutes: "00",
      seconds: "00",
    };

    if (await this.getEndTime()) {
      const { hours, minutes, seconds } = getRelativeTime(
        await store.getters["time/getServerTime"],
        await this.getEndTime()
      );

      time.hours = hours;
      time.minutes = minutes;
      time.seconds = seconds;
    } else if (await this.doesCurrentPomodoroExist()) {
      const { hours, minutes, seconds } = getRelativeTime(
        await store.getters["time/getServerTime"],
        (await store.getters["time/getServerTime"]) + (await this.getDuration())
      );

      time.hours = hours;
      time.minutes = minutes;
      time.seconds = seconds;
    }

    return time;
  },
  async getStartTime() {
    const pomodoro = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getPomodoro"])
    );

    if (pomodoro) {
      return pomodoro.startTime;
    }

    return null;
  },
  async getEndTime() {
    const pomodoro = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getPomodoro"])
    );

    if (pomodoro) {
      return pomodoro.endTime;
    }

    return null;
  },
  async getDuration() {
    const pomodoro = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getPomodoro"])
    );

    if (pomodoro) {
      return pomodoro.duration;
    }

    return null;
  },
  async isRunning() {
    const pomodoro = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getPomodoro"])
    );
    if (pomodoro) {
      return pomodoro.id && pomodoro.isRunning;
    }

    return false;
  },
  async startPomodoro(workDuration, breakDuration, repeat, type) {
    if (await this.isRunning()) {
      return;
    }

    if (type === "fixed") {
      repeat = 0;
    }

    let newPomodoro = this.createNewPomodoro(workDuration, type);
    newPomodoro.isRunning = true;
    newPomodoro.startTime = await store.getters["time/getServerTime"];
    newPomodoro.endTime = newPomodoro.startTime + newPomodoro.duration;

    await store.commit("pomodoro/setPomodoro", newPomodoro);

    if (repeat > 0) {
      let planned = [];
      for (let i = 0; i < repeat; i++) {
        planned.push(this.createNewPomodoro(breakDuration, "break"));

        if (i < repeat - 1) {
          planned.push(this.createNewPomodoro(workDuration, "pomodoro"));
        }
      }
      await store.commit("pomodoro/setPlanned", planned);
    }
  },
  getEmptyPomodoro() {
    return {
      id: null,
      duration: null,
      startTime: null,
      endTime: null,
      isRunning: false,
      type: "pomodoro",
    };
  },
  getDefaultConfiguration() {
    return {
      workDuration: 25,
      breakDuration: 5,
      repeat: 4,
      type: "pomodoro",
      settingsExpanded: false,
    };
  },
  createNewPomodoro(duration, type) {
    let pomodoro = JSON.parse(JSON.stringify(this.getEmptyPomodoro()));
    pomodoro.id = getUuid();
    pomodoro.duration = duration;
    pomodoro.remainingTime = duration;
    pomodoro.type = type;

    return pomodoro;
  },
  async hasPomodoroEnded() {
    return (
      (await this.getEndTime()) <= (await store.getters["time/getServerTime"])
    );
  },
  async pickNextPomodoro() {
    if (store.getters["pomodoro/getPlanned"].length > 0) {
      const planned = JSON.parse(
        JSON.stringify(store.getters["pomodoro/getPlanned"])
      );

      const nextPomodoro = planned.shift();
      await store.commit("pomodoro/setPlanned", planned);

      nextPomodoro.isRunning = true;
      nextPomodoro.startTime = await store.getters["time/getServerTime"];
      nextPomodoro.endTime = nextPomodoro.startTime + nextPomodoro.duration;

      await store.commit("pomodoro/setPomodoro", nextPomodoro);

      return nextPomodoro;
    }
  },
  async pausePomodoro() {
    const pomodoro = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getPomodoro"])
    );

    if (pomodoro) {
      const remainingTime =
        pomodoro.endTime - (await store.getters["time/getServerTime"]);

      if (remainingTime > 0) {
        pomodoro.duration = remainingTime;
        pomodoro.endTime = null;
      }

      pomodoro.isRunning = false;
      await store.commit("pomodoro/setPomodoro", pomodoro);
    }
  },
  async resumePomodoro() {
    const pomodoro = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getPomodoro"])
    );

    if (pomodoro) {
      pomodoro.endTime =
        (await store.getters["time/getServerTime"]) + pomodoro.duration;
      pomodoro.isRunning = true;
      await store.commit("pomodoro/setPomodoro", pomodoro);
    }
  },
  async doesCurrentPomodoroExist() {
    const pomodoro = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getPomodoro"])
    );

    return !!pomodoro;
  },
  async getConfiguration() {
    const configuration = JSON.parse(
      JSON.stringify(store.getters["pomodoro/getConfiguration"])
    );

    if (!configuration) {
      return this.getDefaultConfiguration();
    }

    return configuration;
  },
  async updateConfiguration(configuration) {
    await store.commit("pomodoro/setConfiguration", configuration);
  },
};
