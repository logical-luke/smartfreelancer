import api from "@/services/api";
import store from "@/store";

export default {
  async syncClients() {
    const clients = await api.getClients();

    await store.commit("clients/setClients", clients);
  },
};
