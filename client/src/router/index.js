import { createRouter, createWebHistory } from "vue-router";
import ProjectsPage from "../components/project/ProjectsPage.vue";
import LoginPage from "../components/LoginPage.vue";
import DashboardPage from "../components/dashboard/DashboardPage.vue";
import store from "../store";
import ProjectEditPage from "@/components/project/ProjectEditPage.vue";
import ProjectCreatePage from "@/components/project/ProjectCreatePage.vue";
import TasksPage from "@/components/task/TasksPage.vue";
import ClientsPage from "@/components/client/ClientsPage.vue";
import ClientCreatePage from "@/components/client/ClientCreatePage.vue";
import ClientEditPage from "@/components/client/ClientEditPage.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/login",
      name: "LoginPage",
      component: LoginPage,
    },
    {
      path: "/",
      name: "DashboardPage",
      component: DashboardPage,
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
  ],
});

router.beforeEach((to, from, next) => {
  if (to.name === "LoginPage" && store.getters.isAuthorized) {
    next({ name: "Dashboard" });
  } else {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
      // this route requires auth, check if logged in
      // if not, redirect to login page.
      if (!store.getters.isAuthorized) {
        next({ name: "Login" });
      } else {
        next(); // go to wherever I'm going
      }
    } else {
      next(); // does not require auth, make sure to always call next()!
    }
  }
});

export default router;
