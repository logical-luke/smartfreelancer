import db from "@/services/cache/db";
import store from "@/store";

export default {
  async getInitial() {
    if (await this.exists("locale")) {
      await store.commit("settings/setLocale", await this.get("locale"));
    }
    if (await this.exists("navBarCollapsed")) {
      await store.commit(
        "settings/setNavbarCollapsed",
        (await this.get("navBarCollapsed")) === "1"
      );
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
    if (await this.exists("queue")) {
      await store.commit("synchronization/setQueue", JSON.parse(await this.get("queue")));
    }
    await store.commit("synchronization/setInitialLoaded", true);
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
};
