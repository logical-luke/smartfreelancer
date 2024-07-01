import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import synchronization from "@/services/synchronization";
import cache from "@/services/cache";

const createNewClient = (newClientInput) => {
    return {
        id: getUuid(),
        createdAt: store.getters['time/getServerTime'],
        name: newClientInput?.name,
        avatar: newClientInput?.avatar,
        email: newClientInput?.email,
        phone: newClientInput?.phone,
    }
}

const getClients = () => { return JSON.parse(JSON.stringify(store.getters["clients/getClients"])) };

export default {
    create(newClientInput) {
        let clients = getClients();
        const newClient = createNewClient(newClientInput);
        clients.unshift(newClient);
        store.commit("clients/setClients", clients);
        cache.set('clients', JSON.stringify(clients)).then();
        synchronization.pushToQueue(
            "client",
            "create",
            newClient,
        );
    },
    delete(id) {
        let clients = getClients();
        clients = clients.filter((client) => client.id !== id);
        store.commit("clients/setClients", clients);
        cache.set('clients', JSON.stringify(clients)).then();
        synchronization.pushToQueue(
            "client",
            "delete",
            {id: id}
        );
    },
    update(updatedClient) {
        let clients = getClients();

        clients = clients.map((client) => {
            if (client.id === updatedClient.id) {
                return {...updatedClient};
            }
            return client;
        });
        store.commit("clients/setClients", clients);
        cache.set('clients', JSON.stringify(clients)).then();
        synchronization.pushToQueue(
            "client",
            "update",
            updatedClient
        );
    },
    getById(id) {
        let clients = getClients();
        return clients.filter((client) => client.id === id).pop();
    },
    getTimerOptions() {
        let clients = getClients();
        return clients.all.map((client) => {
            return {
                id: client.id,
                label: client.name,
            };
        });
    }
}
