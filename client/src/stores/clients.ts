import {defineStore} from "pinia";
import api from "@/services/api";

export const useClientsStore = defineStore("clients", {
    state: () => ({
        clients: [],
    }),
    actions: {
        async load() {
            this.clients = await api.clients.get();
        },
        async create(client) {
            const newClient = await api.clients.create(client);
            this.clients.push(newClient);
        },
        async delete(id) {
            await api.clients.delete(id);
            this.clients = this.clients.filter((client) => client.id !== id);
        },
        async update(client) {
            const updatedClient = await api.clients.update(client);
            this.clients = this.clients.map((c) => (c.id === updatedClient.id ? updatedClient : c));
        },
    },
});
