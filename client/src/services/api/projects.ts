import httpClient from "@/services/api/httpClient";
import type Project from "@/interfaces/project";
import type ProjectForm from "@/interfaces/projectForm";

export default {
    async delete(id: string): Promise<void> {
        const response = await httpClient.delete("/projects/" + id);

        if (response.status !== 204) {
            throw new Error(response.data.message);
        }
    },

    async update(id: string, project: ProjectForm): Promise<Project> {
        const response = await httpClient.put("/projects/" + id, project);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async create(project: ProjectForm): Promise<Project> {
        const response = await httpClient.post("/projects", project);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async list(): Promise<Project[]> {
        const response = await httpClient.get("/projects");

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },

    async get(id: string): Promise<Project> {
        const response = await httpClient.get("/projects/" + id);

        if (response.status !== 200) {
            throw new Error(response.data.message);
        }

        return response.data;
    },
}
