<script setup lang="ts">
import { ref, reactive, computed, onMounted } from "vue";
import { useStore } from "vuex";
import Select from "primevue/select";
import i18n from "@/services/locale/i18n"; // Import the i18n instance

const store = useStore();

interface Language {
  name: string;
  code: string;
}

const selected = ref<Language>({
  name: "English",
  code: "en",
});

const languages = reactive<Language[]>([
  { name: "čeština", code: "cs" },
  { name: "Deutsch", code: "de" },
  { name: "English", code: "en" },
  { name: "Española", code: "es" },
  { name: "Français", code: "fr" },
  { name: "Italiano", code: "it" },
  { name: "Polski", code: "pl" },
  { name: "українська мова", code: "uk" },
]);

const getLocale = computed<string>(() => store.getters["settings/getLocale"]);

onMounted(() => {
  const locale = getLocale.value;
  const selectedLanguage = languages.find((lang) => lang.code === locale);
  if (selectedLanguage) {
    selected.value = selectedLanguage;
  }
  i18n.global.locale = locale; // Use the i18n instance
});

function setLanguage(language: Language) {
  const code = language.code;
  i18n.global.locale = code; // Use the i18n instance
  store.dispatch("settings/setLocale", code);
}
</script>

<template>
  <Select
      v-model="selected"
      :options="languages"
      option-label="name"
      class="w-full"
      @update:model-value="setLanguage"
  />
</template>
