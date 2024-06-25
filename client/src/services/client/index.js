import api from "@/services/api";
import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import synchronization from "@/services/synchronization";

export default {
    add(newClient) {
        newClient.id = getUuid();
        newClient.createdAt = store.getters['time/getServerTime'];
        let clients = JSON.parse(JSON.stringify(store.getters["clients/getClients"]));
        clients.unshift(newClient);
        store.commit("clients/setClients", clients);
        synchronization.pushToQueue(
            "client",
            "create",
            newClient
        );
    },
    delete(id) {
        let clients = JSON.parse(JSON.stringify(store.getters["clients/getClients"]));
        clients = clients.filter((client) => client.id !== id);
        store.commit("clients/setClients", clients);
        synchronization.pushToQueue(
            "client",
            "delete",
            {id: id}
        );
    },
}
