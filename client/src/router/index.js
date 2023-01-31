import { createRouter, createWebHistory } from "vue-router";
import ProjectsPage from "@/components/project/ProjectsPage.vue";
import LoginPage from "@/components/LoginPage.vue";
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
import ReportsPage from "@/components/report/ReportsPage.vue";
import TimelinePage from "@/components/timeline/TimelinePage.vue";

const router = createRouter({
  history: createWebHistory(),
  scrollBehavior() {
    return { top: 0 }
  },
  routes: [
    {
      path: "/login",
      name: "LoginPage",
      component: LoginPage,
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
  ],
});

router.beforeEach((to, from, next) => {
  if (to.name === "LoginPage" && store.getters.isAuthorized) {
    next({ name: "TodayPage" });
  } else {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
      // this route requires auth, check if logged in
      // if not, redirect to login page.
      if (!store.getters.isAuthorized) {
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
