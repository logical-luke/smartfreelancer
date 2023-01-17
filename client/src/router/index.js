import {createRouter, createWebHistory} from "vue-router";
import Projects from "../components/Projects.vue";
import Login from "../components/Login.vue";
import Welcome from "../components/Welcome.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "Welcome",
            component: Welcome,
        },
        {
            path: "/login",
            name: "Login",
            component: Login,
        },
        {
            path: "/projects",
            name: "Projects",
            component: Projects,
        }
    ]
});

export default router;
