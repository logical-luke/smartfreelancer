import httpClient from "@/services/api/httpClient";
import type Client from "@/interfaces/client";
import type ClientForm from "@/interfaces/clientForm";

export default {
    async delete(id: string): Promise<void> {
        const response = await httpClient.delete("/clients/" + id);

        if (response.status !== 204) {
            throw new Error(response.data.message);
        }
    },

    async update(id: string, client: ClientForm): Promise<Client> {
        const response = await httpClient.put("/clients/" + id, client);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async create(client: ClientForm): Promise<Client> {
        const response = await httpClient.post("/clients", client);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },


    async list(): Promise<Client[]> {
        const response = await httpClient.get("/clients");

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async get(id: string): Promise<Client> {
        const response = await httpClient.get("/clients/" + id);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },
}
