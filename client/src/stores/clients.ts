import {defineStore} from "pinia";
import api from "@/services/api";
import type {Client, ClientForm} from "@/interfaces/Client";

interface State {
    clients: Client[];
}

export const useClientsStore = defineStore("clients", {
    state: (): State => ({
        clients: [],
    }),
    actions: {
        async load(): Promise<void> {
            this.clients = await api.clients.list();
        },
        async create(client: ClientForm): Promise<void> {
            const newClient = await api.clients.create(client);
            this.clients.push(newClient);
        },
        async delete(id: string) {
            await api.clients.delete(id);
            this.clients = this.clients.filter((client) => client.id !== id);
        },
        async update(id: string, client: ClientForm) {
            const updatedClient = await api.clients.update(id, client);
            this.clients = this.clients.map((c) => (c.id === updatedClient.id ? updatedClient : c));
        },
    },
});
