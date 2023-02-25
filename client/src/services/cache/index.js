import db from "@/services/cache/db";
import store from "@/store";

export default {
  async getInitial() {
    await store.commit("synchronization/setInitialLoaded", true);
  },
  async getLocale() {
    return await db.get("locale");
  },
  async setLocale(locale) {
    return await db.set("locale", locale);
  },
  async clear() {
    return await db.clear();
  }
};
