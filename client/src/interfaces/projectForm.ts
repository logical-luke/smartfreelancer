// client/src/interfaces/projectForm.ts
export default interface ProjectForm {
  name: string;
  description: string | null;
  client: string;
  todoTasks: number;
  inProgressTasks: number;
  blockedTasks: number;
  completedTasks: number;
  dueDate: string;
  avatar: string | null;
}
