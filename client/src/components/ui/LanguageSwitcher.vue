<template>
  <div class="relative">
    <button
      @click="toggle"
      class="navbar-burger pl-2 pr-2 pb-2 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
    >
      <country-flag :country="getFlagCode(getLocale)"></country-flag>
    </button>
    <overlay-panel ref="op" :show-close-icon="true">
      <div class="py-1">
        <button
          v-for="lang in getLanguages"
          :key="lang"
          class="w-full flex text-sm text-gray-700 px-4 py-2 hover:bg-gray-100 hover:text-gray-900"
          @click="setLanguage(lang)"
        >
          <country-flag :country="getFlagCode(lang)"></country-flag>
        </button>
      </div>
    </overlay-panel>
  </div>
</template>

<script>
import CountryFlag from "vue-country-flag-next";
import { mapGetters } from "vuex";
import store from "@/store";
import OverlayPanel from "primevue/overlaypanel";

export default {
  name: "LanguageSwitcher",
  components: { CountryFlag, OverlayPanel },
  data() {
    return {
      showList: false,
    };
  },
  computed: {
    ...mapGetters(["getLanguages", "getLocale"]),
  },
  methods: {
    setLanguage(language) {
      this.$i18n.locale = language;
      store.commit("setLocale", language);
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

<style scoped></style>
