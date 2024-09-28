import httpClient from "@/services/api/httpClient";
import type {Project, ProjectForm} from "@/interfaces/Project";
import type {AxiosResponse} from "axios";
import {mapApiData} from "@/services/api/mappers/apiDataMapper";
import {projectMapperConfig} from "@/services/api/mappers/projectMapperConfig";
import {toAxiosData} from "@/services/api/mappers/axiosRecordMapper";

export default {
    async delete(id: string): Promise<void> {
        const response: AxiosResponse = await httpClient.delete("/projects/" + id);

        if (response.status !== 204) {
            throw new Error(response.data.message);
        }
    },

    async update(id: string, project: ProjectForm): Promise<Project> {
        const response: AxiosResponse = await httpClient.put("/projects/" + id, toAxiosData(project));

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async create(project: ProjectForm): Promise<Project> {
        const response: AxiosResponse = await httpClient.post("/projects", toAxiosData(project));

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async list(): Promise<Project[]> {
        const response: AxiosResponse = await httpClient.get("/projects");

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data.map((projectData: any) => mapApiData<Project>(projectData, projectMapperConfig));
    },

    async get(id: string): Promise<Project> {
        const response: AxiosResponse = await httpClient.get("/projects/" + id);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return mapApiData<Project>(response.data, projectMapperConfig);
    },
}
