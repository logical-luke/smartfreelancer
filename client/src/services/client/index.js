import getUuid from "@/services/uuidGenerator";
import store from "@/store";
import cache from "@/services/cache";

const createNewClient = (newClientInput) => {
    return {
        id: getUuid(),
        createdAt: store.getters['time/getServerTime'],
        name: newClientInput?.name,
        avatar: newClientInput?.avatar,
        email: newClientInput?.email,
        phone: newClientInput?.phone,
        revenue: 0,
        timeWorked: 0,
        ongoingTasks: 0,
        plannedTasks: 0,
        finishedTasks: 0,
        blockedTasks: 0,
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
    },
    delete(id) {
        let clients = getClients();
        clients = clients.filter((client) => client.id !== id);
        store.commit("clients/setClients", clients);
        cache.set('clients', JSON.stringify(clients)).then();
    },
    update(updatedClient) {
        let clients = getClients();

        clients = clients.map((client) => {
            if (client.id === updatedClient.id) {
                return {...client, ...updatedClient};
            }
            return client;
        });
        store.commit("clients/setClients", clients);
        cache.set('clients', JSON.stringify(clients)).then();
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
