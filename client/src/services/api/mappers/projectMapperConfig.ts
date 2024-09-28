import type {MapperConfig} from "@/services/api/mappers/apiDataMapper";
import type {Project} from "@/interfaces/Project";

export const projectMapperConfig: MapperConfig<Project> = {
    id: {key: 'id', required: true},
    name: {key: 'name', required: true},
    description: {key: 'description', required: true},
    clientId: {key: 'clientId', required: true},
    revenue: {key: 'revenue', default: 0},
    timeWorked: {key: 'timeWorked', default: 0},
    todoTasks: {key: 'todoTasks', default: 0},
    inProgressTasks: {key: 'inProgressTasks', default: 0},
    blockedTasks: {key: 'blockedTasks', default: 0},
    completedTasks: {key: 'completedTasks', default: 0},
    avatar: {key: 'avatar', default: null},
    dueDate: {key: 'dueDate', required: true},
    income: {key: 'income', default: 0},
    expenses: {key: 'expenses', default: 0},
    invoiced: {key: 'invoiced', default: 0},
    paid: {key: 'paid', default: 0},
    estimated: {key: 'estimated', default: 0},
    timeEstimated: {key: 'timeEstimated', default: 0},
    timeLeft: {key: 'timeLeft', default: 0},
}