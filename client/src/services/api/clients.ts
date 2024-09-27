import httpClient from "@/services/api/httpClient";
import type Client from "@/interfaces/Client";
import type ClientForm from "@/interfaces/clientForm";
import type {AxiosResponse} from "axios";
import {clientMapperConfig} from "@/services/api/mappers/clientMapperConfig";
import {mapApiData} from "@/services/api/mappers/apiDataMapper";
import {toAxiosData} from "@/services/api/mappers/axiosRecordMapper";

export default {
    async delete(id: string): Promise<void> {
        const response: AxiosResponse = await httpClient.delete("/clients/" + id);

        if (response.status !== 204) {
            throw new Error(response.data.message);
        }
    },

    async update(id: string, client: ClientForm): Promise<Client> {
        const response: AxiosResponse = await httpClient.put("/clients/" + id, toAxiosData(client));

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return mapApiData<Client>(response.data, clientMapperConfig);
    },

    async create(client: ClientForm): Promise<Client> {
        const response: AxiosResponse = await httpClient.post("/clients", toAxiosData(client));

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return mapApiData<Client>(response.data, clientMapperConfig);
    },

    async list(): Promise<Client[]> {
        const response: AxiosResponse = await httpClient.get("/clients");

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data.map((clientData: any) => mapApiData<Client>(clientData, clientMapperConfig));
    },

    async get(id: string): Promise<Client> {
        const response: AxiosResponse = await httpClient.get("/clients/" + id);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return mapApiData<Client>(response.data, clientMapperConfig);
    },
}
