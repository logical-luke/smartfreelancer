import type {MapperConfig} from "@/services/api/mappers/apiDataMapper";
import type Client from "@/interfaces/Client";

export const clientMapperConfig: MapperConfig<Client> = {
    id: {key: 'id', required: true},
    ownerId: {key: 'ownerId', required: true},
    name: {key: 'name', required: true},
    avatar: {key: 'avatar', default: null},
    phone: {key: 'phone', default: null},
    email: {key: 'email', default: null},
    createdAt: {key: 'createdAt', required: true, transform: (value) => new Date(value).getTime()},
    revenue: {key: 'revenue', default: 0},
    timeWorked: {key: 'timeWorked', default: 0},
    todoTasks: {key: 'todoTasks', default: 0},
    inProgressTasks: {key: 'in_progress_tasks', default: 0},
    blockedTasks: {key: 'blockedTasks', default: 0},
    completedTasks: {key: 'completedTasks', default: 0},
    internal: {key: 'internal', default: false},
    income: {key: 'income', default: 0},
    expenses: {key: 'expenses', default: 0},
    invoiced: {key: 'invoiced', default: 0},
    paid: {key: 'paid', default: 0},
    incomeEstimated: {key: 'incomeEstimated', default: 0},
    timeEstimated: {key: 'timeEstimated', default: 0},
}