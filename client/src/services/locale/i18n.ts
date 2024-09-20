import { createI18n } from "vue-i18n";

const messages = {};

const i18n = createI18n({
  legacy: false,
  locale: "en-US",
  messages: messages,
  silentTranslationWarn: true
});

export default i18n;
