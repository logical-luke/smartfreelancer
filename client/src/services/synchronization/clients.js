import api from "@/services/api";
import store from "@/store";
import cache from "@/services/cache";

export default {
  async syncClients() {
    const clients = await api.getClients();

    await store.commit("clients/setClients", clients);
    await cache.set("clients", JSON.stringify(clients));
  },
};
