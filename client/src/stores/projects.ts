import { defineStore } from "pinia";
import api from "@/services/api";
import type Project from "@/interfaces/project";
import type ProjectForm from "@/interfaces/projectForm";

interface State {
  projects: Project[];
}

export const useProjectsStore = defineStore("projects", {
  state: (): State => ({
    projects: [],
  }),
  actions: {
    async load(): Promise<void> {
      this.projects = await api.projects.list();
    },
    async create(project: ProjectForm): Promise<void> {
      const newProject = await api.projects.create(project);
      this.projects.push(newProject);
    },
    async delete(id: string) {
      await api.projects.delete(id);
      this.projects = this.projects.filter((project) => project.id !== id);
    },
    async update(id: string, project: ProjectForm) {
      const updatedProject = await api.projects.update(id, project);
      this.projects = this.projects.map((p) => (p.id === updatedProject.id ? updatedProject : p));
    },
  },
});
