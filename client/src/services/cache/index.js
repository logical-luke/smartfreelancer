import db from "@/services/cache/db";
import store from "@/store";

export default {
  async loadLocale(){
    if (await this.exists("locale")) {
      await store.commit("settings/setLocale", await this.get("locale"));
    }
  },
  async getInitial() {
    if (await this.exists("timerMode")) {
      await store.commit("timer/setTimerMode", await this.get("timerMode"));
    }
    if (await this.exists("clients")) {
      await store.commit(
        "clients/setClients",
        JSON.parse(await this.get("clients"))
      );
    }
    if (await this.exists("projects")) {
      await store.commit(
        "projects/setProjects",
        JSON.parse(await this.get("projects"))
      );
    }
    if (await this.exists("tasks")) {
      await store.commit("tasks/setTasks", JSON.parse(await this.get("tasks")));
    }
    if (await this.exists("timer")) {
      await store.commit("timer/setTimer", JSON.parse(await this.get("timer")));
    }
    if (await this.exists("pomodoro")) {
      await store.commit(
        "pomodoro/setPomodoro",
        JSON.parse(await this.get("pomodoro"))
      );
    }
    if (await this.exists("planned")) {
      await store.commit(
        "pomodoro/setPlanned",
        JSON.parse(await this.get("planned"))
      );
    }
    if (await this.exists("pomodoro-configuration")) {
      await store.commit(
        "pomodoro/setConfiguration",
        JSON.parse(await this.get("pomodoro-configuration"))
      );
    }
  },
  async clear() {
    return await db.clear();
  },
  async get(key) {
    return await db.get(key);
  },
  async exists(key) {
    return (await db.get(key)) !== null;
  },
  async set(key, value) {
    return await db.set(key, value);
  },
  async remove(key) {
    return await db.remove(key);
  },
};
