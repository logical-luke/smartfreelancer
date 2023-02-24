import api from "@/services/api";
import store from "@/store";

export default async function getTimeEntries(payload) {
  const timeEntries = await api.getTimeEntries(payload);

  await store.commit("timeEntries/setTimeEntries", timeEntries);
}
