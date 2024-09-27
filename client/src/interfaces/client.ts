export default interface Client {
    id: string;
    ownerId: string;
    name: string | null;
    avatar: string | null;
    phone: string | null;
    email: string | null;
    createdAt: number;
    revenue: number;
    timeWorked: number;
    timeEstimated: number;
    todoTasks: number;
    inProgressTasks: number;
    blockedTasks: number;
    completedTasks: number;
    internal: boolean;
    income: number;
    expenses: number;
    invoiced: number;
    paid: number;
    incomeEstimated: number;
}