<template>
  <dropdown v-model="selected"
            :options="languages"
            optionLabel="name"
            class="w-full"
            @update:model-value="setLanguage"
  />
</template>

<script>
import {mapGetters} from "vuex";
import store from "@/store";
import cache from "@/services/cache";
import Dropdown from 'primevue/dropdown';

export default {
  name: "LanguageSwitcher",
  components: {
    Dropdown,
  },
  data() {
    return {
      selected: {
        name: "English",
        code: "en"
      },
      languages: [
        {
          name: "čeština",
          code: "cs"
        },
        {
          name: "Deutsch",
          code: "de"
        },
        {
          name: "English",
          code: "en"
        },
        {
          name: "Española",
          code: "es"
        },
        {
          name: "Français",
          code: "fr"
        },
        {
          name: "Italiano",
          code: "it"
        },
        {
          name: "Polski",
          code: "pl"
        },
        {
          name: "українська мова",
          code: "uk"
        },
      ]
    };
  },
  computed: {
    ...mapGetters("settings", ["getLocale"]),
  },
  methods: {
    setLanguage(language) {
      const code = language.code;
      console.log(code);
      this.$i18n.locale = code;
      store.dispatch("settings/setLocale", code);
      cache.set("locale", code);
    },
  },
  mounted() {
    const locale = this.getLocale;
    const selectedLanguage = this.languages.find(lang => lang.code === locale);
    if (selectedLanguage) {
      this.selected = selectedLanguage;
    }
    this.$i18n.locale = locale;
  },
};
</script>
