import { createApp } from "vue";
import App from "./App.vue";

import router from "./router";
import store from "./store";
import VueCookies from "vue-cookies";
import "./tailwind/tailwind.css";
import "primevue/resources/themes/tailwind-light/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

import ConfirmationService from "primevue/confirmationservice";
import PrimeVue from "primevue/config";
import { createI18n } from "vue-i18n";

import cs from "../src/locale/cs.json";
import de from "../src/locale/de.json";
import en from "../src/locale/en.json";
import fr from "../src/locale/fr.json";
import it from "../src/locale/it.json";
import pl from "../src/locale/pl.json";
import pt from "../src/locale/pt.json";
import ru from "../src/locale/ru.json";
import uk from "../src/locale/uk.json";

const app = createApp(App);

const messages = {
  cs: cs,
  de: de,
  en: en,
  fr: fr,
  it: it,
  pl: pl,
  pt: pt,
  ru: ru,
  uk: uk
}

const i18n = createI18n({
  locale: 'en',
  fallbackLocale: 'en',
  messages: messages
});

app.use(i18n);

app.use(VueCookies);
app.use(store);
app.use(router);

app.use(PrimeVue);
app.use(ConfirmationService);

app.mount("#app");
