import {createApp} from "vue";
import App from "./App.vue";
import i18n from "@/services/locale/i18n";
import router from "./router";
import {VueCookieNext} from 'vue-cookie-next'
import {createPinia} from "pinia";
import "./tailwind/tailwind.css";
import "primeicons/primeicons.css";
import Aura from '@primevue/themes/aura';
import Tooltip from "primevue/tooltip";


import PrimeVue from "primevue/config";
import ConfirmationService from "primevue/confirmationservice";
import ToastService from "primevue/toastservice";
import {definePreset} from '@primevue/themes';

const app = createApp(App);

app.use(i18n);
app.use(VueCookieNext);
app.use(createPinia());
app.use(router);

const AuraIndigo = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{indigo.50}',
            100: '{indigo.100}',
            200: '{indigo.200}',
            300: '{indigo.300}',
            400: '{indigo.400}',
            500: '{indigo.500}',
            600: '{indigo.600}',
            700: '{indigo.700}',
            800: '{indigo.800}',
            900: '{indigo.900}',
            950: '{indigo.950}'
        }
    }
});

app.use(PrimeVue, {
    theme: {
        preset: AuraIndigo,
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
