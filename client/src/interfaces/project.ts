export default interface Project {
  id: string;
  name: string;
  description: string | null;
  clientId: string;
  revenue: number;
  timeWorked: number;
  todoTasks: number;
  inProgressTasks: number;
  blockedTasks: number;
  completedTasks: number;
  avatar: string | null;
  dueDate: string;
  income: number;
  expenses: number;
  invoiced: number;
  paid: number;
  estimated: number;
  timeEstimated: number;
  timeLeft: number;
}