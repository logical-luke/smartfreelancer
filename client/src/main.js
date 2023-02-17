import { createApp } from "vue";
import App from "./App.vue";

import router from "./router";
import store from "./store";
import VueCookies from "vue-cookies";
import "./tailwind/tailwind.css";
import 'primevue/resources/themes/tailwind-light/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';

import ConfirmationService from 'primevue/confirmationservice';
import PrimeVue from "primevue/config";

const app = createApp(App);

app.use(VueCookies);
app.use(store);
app.use(router);

app.use(PrimeVue);
app.use(ConfirmationService);

app.mount("#app");
