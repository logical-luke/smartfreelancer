import db from "@/services/cache/db";
import store from "@/store";

export default {
  async getInitial() {
    const locale = await this.get("locale");
    if (locale) {
      await store.commit("settings/setLocale", locale);
    }
    const timer = await this.get("timer");
    if (timer) {
      await store.commit("timer/setTimer", timer);
    }
    await store.commit("synchronization/setInitialLoaded", true);
  },
  async clear() {
    return await db.clear();
  },
  async get(key) {
    return await db.get(key);
  },
  async set(key, value) {
    return await db.set(key, value);
  },
};
