<template>
  <div class="relative flex justify-center">
    <button
      type="button"
      @click="toggle"
      class="navbar-burger bg-indigo-500 text-white flex items-center rounded focus:outline-none"
    >
      <country-flag
        :size="size"
        :country="getFlagCode(getLocale)"
      ></country-flag>
    </button>
    <overlay-panel ref="op" :show-close-icon="false">
      <button
        v-for="lang in languages"
        :key="lang"
        class="w-full flex text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
        @click="setLanguage(lang)"
      >
        <country-flag
          :shadow="true"
          :country="getFlagCode(lang)"
        ></country-flag>
      </button>
    </overlay-panel>
  </div>
</template>

<script>
import CountryFlag from "vue-country-flag-next";
import { mapGetters } from "vuex";
import store from "@/store";
import cache from "@/services/cache"
import OverlayPanel from "primevue/overlaypanel";

export default {
  name: "LanguageSwitcher",
  components: { CountryFlag, OverlayPanel },
  props: {
    size: {
      type: String,
      default: "normal",
    },
  },
  data() {
    return {
      showList: false,
    };
  },
  computed: {
    ...mapGetters("settings", ["getLocale"]),
    languages() {
      return ["pl", "en", "de", "fr", "pt", "ru", "uk", "it", "cs"].sort();
    },
  },
  methods: {
    setLanguage(language) {
      this.$i18n.locale = language;
      store.dispatch("settings/setLocale", language);
      cache.set("locale", language);
      this.toggle();
    },
    toggle(event) {
      this.$refs.op.toggle(event);
    },
    getFlagCode(country) {
      const mapping = {
        pl: "pl",
        de: "de",
        fr: "fr",
        pt: "pt",
        ru: "ru",
        uk: "ua",
        it: "it",
        cs: "cz",
        en: "gb",
      };

      return mapping[country];
    },
  },
  mounted() {
    this.$i18n.locale = this.getLocale;
  },
};
</script>

<style scoped>
span.flag.normal-flag {
  margin: 0 -6px 0 -5px;
}
</style>
