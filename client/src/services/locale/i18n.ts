import { createI18n } from "vue-i18n";

const messages = {};

const i18n = createI18n({
  locale: "en",
  messages: messages,
  silentTranslationWarn: true
});

export default i18n;
