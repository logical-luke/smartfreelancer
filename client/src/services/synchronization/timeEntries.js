import api from "@/services/api";
import store from "@/store";

export default {
  async syncTimeEntries(payload) {
    const timeEntries = await api.getTimeEntries(payload);

    await store.commit("timeEntries/setTimeEntries", timeEntries);
  },
  async createTimeEntry(payload) {
    const timeEntry = await api.createTimeEntry(payload);
  },
};
