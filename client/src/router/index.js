import { createRouter, createWebHistory } from "vue-router";
import Projects from "../components/Projects.vue";
import Login from "../components/Login.vue";
import Dashboard from "../components/Dashboard.vue";
import store from "../store";
import ProjectEdit from "@/components/ProjectEdit.vue";
import ProjectCreate from "@/components/ProjectCreate.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name: "Dashboard",
      component: Dashboard,
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: "/login",
      name: "Login",
      component: Login,
    },
    {
      path: "/projects",
      name: "Projects",
      meta: {
        requiresAuth: true,
      },
      component: Projects,
    },
    {
      path: "/project/edit/:id",
      name: "ProjectEdit",
      meta: {
        requiresAuth: true,
      },
      component: ProjectEdit,
    },
    {
      path: "/project/create",
      name: "ProjectCreate",
      meta: {
        requiresAuth: true,
      },
      component: ProjectCreate,
    }
  ],
});

router.beforeEach((to, from, next) => {
  if (to.name === 'Login' && store.getters.isAuthorized) {
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
