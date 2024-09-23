export default interface Project {
  id: string;
  name: string;
  description: string | null;
  clientId: string;
  todoTasks: number;
  inProgressTasks: number;
  blockedTasks: number;
  completedTasks: number;
  avatar: string|null;
  dueDate: string;
}
