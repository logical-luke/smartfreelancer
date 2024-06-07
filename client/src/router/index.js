import { createRouter, createWebHistory } from "vue-router";
import LoginPage from "@/views/authorization/LoginPage.vue";
import store from "../store";
import ProjectEditPage from "@/views/project/ProjectEditPage.vue";
import ProjectCreatePage from "@/views/project/ProjectCreatePage.vue";
import ClientCreatePage from "@/views/client/ClientCreatePage.vue";
import ClientEditPage from "@/views/client/ClientEditPage.vue";
import TaskEditPage from "@/views/task/TaskEditPage.vue";
import TaskCreatePage from "@/views/task/TaskCreatePage.vue";
import TodayPage from "@/views/today/TodayPage.vue";
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

const router = createRouter({
  history: createWebHistory(),
  scrollBehavior() {
    return { top: 0 };
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
      name: "TodayPage",
      component: TodayPage,
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
      path: "/project/edit/:id",
      name: "ProjectEditPage",
      meta: {
        requiresAuth: true,
      },
      component: ProjectEditPage,
    },
    {
      path: "/project/create",
      name: "ProjectCreatePage",
      meta: {
        requiresAuth: true,
      },
      component: ProjectCreatePage,
    },
    {
      path: "/client/create",
      name: "ClientCreatePage",
      meta: {
        requiresAuth: true,
      },
      component: ClientCreatePage,
    },
    {
      path: "/client/edit/:id",
      name: "ClientEditPage",
      meta: {
        requiresAuth: true,
      },
      component: ClientEditPage,
    },
    {
      path: "/task/edit/:id",
      name: "TaskEditPage",
      meta: {
        requiresAuth: true,
      },
      component: TaskEditPage,
    },
    {
      path: "/task/create",
      name: "TaskCreatePage",
      meta: {
        requiresAuth: true,
      },
      component: TaskCreatePage,
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

router.beforeEach((to, from, next) => {
  if (
    (to.name === "LoginPage" || to.name === "RegistrationPage") &&
    store.getters["authorization/isAuthorized"]
  ) {
    next({ name: "TodayPage" });
  } else {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
      // this route requires auth, check if logged in
      // if not, redirect to login page.
      if (!store.getters["authorization/isAuthorized"]) {
        next({ name: "LoginPage" });
      } else {
        next(); // go to wherever I'm going
      }
    } else {
      next(); // does not require auth, make sure to always call next()!
    }
  }
});

export default router;
