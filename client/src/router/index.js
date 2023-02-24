import { createRouter, createWebHistory } from "vue-router";
import ProjectsPage from "@/components/project/ProjectsPage.vue";
import LoginPage from "@/components/authorization/LoginPage.vue";
import store from "../store";
import ProjectEditPage from "@/components/project/ProjectEditPage.vue";
import ProjectCreatePage from "@/components/project/ProjectCreatePage.vue";
import TasksPage from "@/components/task/TasksPage.vue";
import ClientsPage from "@/components/client/ClientsPage.vue";
import ClientCreatePage from "@/components/client/ClientCreatePage.vue";
import ClientEditPage from "@/components/client/ClientEditPage.vue";
import TaskEditPage from "@/components/task/TaskEditPage.vue";
import TaskCreatePage from "@/components/task/TaskCreatePage.vue";
import TodayPage from "@/components/today/TodayPage.vue";
import MetricsPage from "@/components/metric/MetricsPage.vue";
import TimelinePage from "@/components/timeline/TimelinePage.vue";
import RegistrationPage from "@/components/authorization/RegistrationPage.vue";
import NotFoundPage from "@/components/NotFoundPage.vue";
import GoogleLoginPage from "@/components/authorization/GoogleLoginPage.vue";
import AutomationsPage from "@/components/automation/AutomationsPage.vue";
import CalendarPage from "@/components/calendar/CalendarPage.vue";
import ExpensesPage from "@/components/expense/ExpensesPage.vue";
import ForecastsPage from "@/components/forecast/ForecastsPage.vue";
import SettingsPage from "@/components/setting/SettingsPage.vue";
import InvoicesPage from "@/components/invoice/InvoicesPage.vue";
import ShareableReportsPage from "@/components/shareable-reports/ShareableReportsPage.vue";

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
      path: "/metrics",
      name: "MetricsPage",
      component: MetricsPage,
      meta: {
        requiresAuth: true,
      },
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
      path: "/tasks",
      name: "TasksPage",
      meta: {
        requiresAuth: true,
      },
      component: TasksPage,
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
      path: "/timeline",
      name: "TimelinePage",
      meta: {
        requiresAuth: true,
      },
      component: TimelinePage,
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
      path: "/forecasts",
      name: "ForecastsPage",
      meta: {
        requiresAuth: true,
      },
      component: ForecastsPage,
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
      path: "/shareable-reports",
      name: "ShareableReportsPage",
      meta: {
        requiresAuth: true,
      },
      component: ShareableReportsPage,
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
