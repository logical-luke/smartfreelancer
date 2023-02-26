import api from "@/services/api";
import store from "@/store";
import cache from "@/services/cache";

export default {
  async syncClients() {
    let clients = await api.getClients();
    if (!clients) {
      clients = [];
    }

    await store.commit("clients/setClients", clients);
    await cache.set("clients", JSON.stringify(clients));
  },
};
