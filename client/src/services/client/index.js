import api from "@/services/api";
import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import synchronization from "@/services/synchronization";

const emptyClient = {
    id: null,
    createdAt: null,
    name: null,
    description: null,
    avatar: null,
    email: null,
    phone: null,
};

function mapClientProperties(newClientInput, newClient) {
    for (let key in newClientInput) {
        if (newClientInput.hasOwnProperty(key)) {
            newClient[key] = newClientInput[key];
        }
    }
}

const createNewClient = (newClientInput) => {
    let newClient = JSON.parse(JSON.stringify(emptyClient));

    newClient.id = getUuid();
    newClient.createdAt = store.getters['time/getServerTime'];
    mapClientProperties(newClientInput, newClient);

    return newClient;
}

export default {
    add(newClientInput) {
        let clients = JSON.parse(JSON.stringify(store.getters["clients/getClients"]));
        const newClient = createNewClient(newClientInput);
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
