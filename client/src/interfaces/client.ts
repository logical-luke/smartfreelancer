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
    ongoingTasks: number;
    plannedTasks: number;
    finishedTasks: number;
    blockedTasks: number;
}
