<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import Textarea from 'primevue/textarea';
import SectionWrapper from "@/components/SectionWrapper.vue";

const { t } = useI18n();

interface Props {
  dailyNotes: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:dailyNotes', notes: string): void;
}>();
</script>

<template>
  <SectionWrapper :title="t('Daily Notes')" icon="pi pi-pencil" color="purple">
    <Textarea
      :value="dailyNotes"
      :placeholder="t('Add your daily notes here...')"
      :autoResize="true"
      rows="3"
      class="w-full bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-700 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
      @input="$emit('update:dailyNotes', $event.target.value)"
    />
  </SectionWrapper>
</template>