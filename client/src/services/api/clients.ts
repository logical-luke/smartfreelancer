import httpClient from "@/services/api/httpClient";

export default {
    async delete(id: string): Promise<any> {
        const response = await httpClient.delete("/clients/" + id);

        if (response.status !== 204) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async update(client: any): Promise<any> {
        const response = await httpClient.put("/clients/" + client.id, client);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async create(client: any): Promise<any> {
        const response = await httpClient.post("/clients", client);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },


    async get(): Promise<any> {
        const response = await httpClient.get("/clients");

        return response.data;
    },
}
