<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import Textarea from 'primevue/textarea';
import SectionWrapper from "@/components/SectionWrapper.vue";

const { t } = useI18n();

interface Props {
  focusNotes: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'update:focusNotes', notes: string): void;
}>();
</script>

<template>
  <SectionWrapper title="Task Notes" icon="pi pi-pencil" color="purple">
    <Textarea
      :value="focusNotes"
      :placeholder="t('Add your task notes here...')"
      :autoResize="true"
      rows="3"
      class="w-full bg-white dark:bg-gray-700"
      @input="emit('update:focusNotes', $event.target.value)"
    />
  </SectionWrapper>
</template>
