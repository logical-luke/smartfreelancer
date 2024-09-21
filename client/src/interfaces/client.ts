export default interface Client {
    id: string;
    ownerId: string;
    name: string;
    avatar: string|null;
    phone: string|null;
    email: string|null;
    createdAt: number;
    revenue: number;
    timeWorked: number;
    todoTasks: number;
    inProgressTasks: number;
    blockedTasks: number;
    completedTasks: number;
    internal: boolean;
}
