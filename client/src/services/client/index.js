import api from "@/services/api";
import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import synchronization from "@/services/synchronization";

export default {
    async addClient(newClient) {
        newClient.id = getUuid();
        let clients = JSON.parse(JSON.stringify(store.getters["clients/getClients"]));
        clients.unshift(newClient);
        store.commit("clients/setClients", clients);
        await synchronization.pushToQueue(
            "client",
            "create",
            newClient
        );
    }
}
