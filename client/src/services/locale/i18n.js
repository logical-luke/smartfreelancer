import cs from "@/locale/cs.json";
import de from "@/locale/de.json";
import en from "@/locale/en.json";
import fr from "@/locale/fr.json";
import it from "@/locale/it.json";
import pl from "@/locale/pl.json";
import es from "@/locale/es.json";
import uk from "@/locale/uk.json";
import { createI18n } from "vue-i18n";

const messages = {
  cs: cs,
  de: de,
  en: en,
  fr: fr,
  it: it,
  pl: pl,
  es: es,
  uk: uk,
};

const i18n = createI18n({
  locale: "en",
  fallbackLocale: "en",
  messages: messages,
});

export default i18n;
