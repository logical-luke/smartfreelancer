import { createApp } from "vue";
import App from "./App.vue";

import router from "./router";
import store from "./store";
import "./tailwind/tailwind.css";
import "primevue/resources/themes/tailwind-light/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

import i18n from "@/services/locale/i18n";

import PrimeVue from "primevue/config";
import ConfirmationService from "primevue/confirmationservice";
import ToastService from 'primevue/toastservice';

const app = createApp(App);

app.use(i18n);
app.use(store);
app.use(router);

app.use(PrimeVue);
app.use(ConfirmationService);
app.use(ToastService);

app.mount("#app");
