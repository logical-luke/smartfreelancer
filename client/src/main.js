import { createApp } from "vue";
import App from "./App.vue";

import router from "./router";
import store from "./store";
import VueCookies from "vue-cookies";

import "./tailwind/tailwind.css";

const app = createApp(App);

app.use(VueCookies);
app.use(router);
app.use(store);

app.mount("#app");
