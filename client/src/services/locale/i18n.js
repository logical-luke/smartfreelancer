import cs from "@/locale/cs.json";
import de from "@/locale/de.json";
import en from "@/locale/en.json";
import fr from "@/locale/fr.json";
import it from "@/locale/it.json";
import pl from "@/locale/pl.json";
import pt from "@/locale/pt.json";
import ru from "@/locale/ru.json";
import uk from "@/locale/uk.json";
import { createI18n } from "vue-i18n";

const messages = {
  cs: cs,
  de: de,
  en: en,
  fr: fr,
  it: it,
  pl: pl,
  pt: pt,
  ru: ru,
  uk: uk,
};

const i18n = createI18n({
  locale: "en",
  fallbackLocale: "en",
  messages: messages,
});

export default i18n;
