export default interface Task {
  id: number;
  title: string;
  completed: boolean;
  dueDate?: string;
  scheduledDate?: string;
  clientId: string;
  projectId?: string;
  status: 'Todo' | 'In Progress' | 'Blocked' | 'Completed';
  timeEstimate?: number;
  trackedTime?: number;
  estimatedRevenue?: number;
  subtasks?: Task[];
}