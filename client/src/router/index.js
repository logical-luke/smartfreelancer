import { createRouter, createWebHistory } from "vue-router";
import ProjectsView from "@/components/Projects.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: ProjectsView,
    },
  ],
});

export default router;
