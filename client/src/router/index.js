import { createRouter, createWebHistory } from "vue-router";
import LoginPage from "@/components/authorization/LoginPage.vue";
import store from "../store";
import ProjectEditPage from "@/components/project/ProjectEditPage.vue";
import ProjectCreatePage from "@/components/project/ProjectCreatePage.vue";
import ClientCreatePage from "@/components/client/ClientCreatePage.vue";
import ClientEditPage from "@/components/client/ClientEditPage.vue";
import TaskEditPage from "@/components/task/TaskEditPage.vue";
import TaskCreatePage from "@/components/task/TaskCreatePage.vue";
import TodayPage from "@/components/today/TodayPage.vue";
import ReportsPage from "@/components/report/ReportsPage.vue";
import RegistrationPage from "@/components/authorization/RegistrationPage.vue";
import NotFoundPage from "@/components/NotFoundPage.vue";
import GoogleLoginPage from "@/components/authorization/GoogleLoginPage.vue";
import AutomationsPage from "@/components/automation/AutomationsPage.vue";
import CalendarPage from "@/components/calendar/CalendarPage.vue";
import ExpensesPage from "@/components/expense/ExpensesPage.vue";
import SettingsPage from "@/components/setting/SettingsPage.vue";
import InvoicesPage from "@/components/invoice/InvoicesPage.vue";
import WorkHubPage from "@/components/workhub/WorkHubPage.vue";

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
      path: "/workhub",
      name: "WorkHubPage",
      meta: {
        requiresAuth: true,
      },
      component: WorkHubPage,
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
