interface Client {
    id: string;
    ownerId: string;
    name: string;
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

interface ClientForm {
    name: string;
    avatar: string|null;
    phone: string|null;
    email: string|null;
}

export type {Client, ClientForm};