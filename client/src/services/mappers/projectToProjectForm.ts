import type { Project, ProjectForm } from '@/interfaces/Project';

export default function projectToProjectForm(project: Project): ProjectForm {
  return {
    name: project.name,
    description: project.description,
    clientId: project.clientId,
    todoTasks: project.todoTasks,
    inProgressTasks: project.inProgressTasks,
    blockedTasks: project.blockedTasks,
    completedTasks: project.completedTasks,
    dueDate: project.dueDate,
    avatar: project.avatar,
  };
}