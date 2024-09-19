import { createApp } from "vue";
import App from "./App.vue";

import router from "./router";
import { createPinia} from "pinia";
import store from "./store";
import "./tailwind/tailwind.css";
import "primeicons/primeicons.css";
import Aura from '@primevue/themes/aura';
import Tooltip from "primevue/tooltip";

import i18n from "@/services/locale/i18n";

import PrimeVue from "primevue/config";
import ConfirmationService from "primevue/confirmationservice";
import ToastService from "primevue/toastservice";

const app = createApp(App);

app.use(i18n);
app.use(createPinia());
app.use(store);
app.use(router);

app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            cssLayer: {
                name: 'primevue',
                order: 'tailwind-base, primevue, tailwind-utilities'
            }
        }
    }
});

app.use(ConfirmationService);
app.use(ToastService);

app.directive("tooltip", Tooltip);

app.mount("#app");
