import {createRouter, createWebHistory} from "vue-router";
import LoginPage from "@/views/authorization/LoginPage.vue";
import DeepWorkHubPage from "@/views/deepWorkHub/DeepWorkHubPage.vue";
import ReportsPage from "@/views/report/ReportsPage.vue";
import RegistrationPage from "@/views/authorization/RegistrationPage.vue";
import NotFoundPage from "@/views/NotFoundPage.vue";
import GoogleLoginPage from "@/views/authorization/GoogleLoginPage.vue";
import AutomationsPage from "@/views/automation/AutomationsPage.vue";
import CalendarPage from "@/views/calendar/CalendarPage.vue";
import ExpensesPage from "@/views/expense/ExpensesPage.vue";
import SettingsPage from "@/views/setting/SettingsPage.vue";
import InvoicesPage from "@/views/invoice/InvoicesPage.vue";
import ClientsPage from "../views/client/ClientsPage.vue";
import ProjectsPage from "../views/project/ProjectsPage.vue";
import TasksPage from "../views/task/TasksPage.vue";
import {useAuthorizationStore} from "@/stores/auth";

const router = createRouter({
    history: createWebHistory(),
    scrollBehavior() {
        return {top: 0};
    },
    routes: [
        {
            path: "/login",
            name: "LoginPage",
            component: LoginPage,
        },
        {
            path: "/register",
            name: "RegistrationPage",
            component: RegistrationPage,
        },
        {
            path: "/google/login",
            name: "GoogleLoginPage",
            component: GoogleLoginPage,
        },
        {
            path: "/",
            name: "DeepWorkHubPage",
            component: DeepWorkHubPage,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: "/reports",
            name: "ReportsPage",
            component: ReportsPage,
            meta: {
                requiresAuth: true,
            },
        },
        {
            path: "/automations",
            name: "AutomationsPage",
            meta: {
                requiresAuth: true,
            },
            component: AutomationsPage,
        },
        {
            path: "/calendar",
            name: "CalendarPage",
            meta: {
                requiresAuth: true,
            },
            component: CalendarPage,
        },
        {
            path: "/expenses",
            name: "ExpensesPage",
            meta: {
                requiresAuth: true,
            },
            component: ExpensesPage,
        },
        {
            path: "/settings",
            name: "SettingsPage",
            meta: {
                requiresAuth: true,
            },
            component: SettingsPage,
        },
        {
            path: "/invoices",
            name: "InvoicesPage",
            meta: {
                requiresAuth: true,
            },
            component: InvoicesPage,
        },
        {
            path: "/clients",
            name: "ClientsPage",
            meta: {
                requiresAuth: true,
            },
            component: ClientsPage,
        },
        {
            path: "/projects",
            name: "ProjectsPage",
            meta: {
                requiresAuth: true,
            },
            component: ProjectsPage,
        },
        {
            path: "/tasks",
            name: "TasksPage",
            meta: {
                requiresAuth: true,
            },
            component: TasksPage,
        },
        {
            path: "/:pathMatch(.*)*",
            name: "NotFoundPage",
            component: NotFoundPage,
        },
    ],
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthorizationStore();
    await authStore.getTokensFromCookies();

    if (
        (
            to.name === "LoginPage" || to.name === "RegistrationPage")
        && authStore.isAuthorized
    ) {
        next({name: "DeepWorkHubPage"});
    } else {
        if (to.matched.some((record) => record.meta.requiresAuth)) {
            if (!authStore.isAuthorized) {
                next({name: "LoginPage"});
            } else {
                next();
            }
        } else {
            next();
        }
    }
});

export default router;
