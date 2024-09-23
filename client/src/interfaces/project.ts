export default interface Project {
  id: string;
  name: string;
  description: string | null;
  client: string;
  todoTasks: number;
  inProgressTasks: number;
  blockedTasks: number;
  completedTasks: number;
  dueDate: string;
}
