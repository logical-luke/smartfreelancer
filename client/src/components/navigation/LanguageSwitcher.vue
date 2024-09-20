<script setup lang="ts">
import { ref, reactive } from "vue";
import Select from "primevue/select";

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
</script>

<template>
  <Select
      v-model="selected"
      :options="languages"
      option-label="name"
      class="w-full"
  />
</template>
