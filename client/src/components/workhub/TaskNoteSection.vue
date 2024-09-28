<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import Textarea from 'primevue/textarea';

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
  <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-xl shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-200 mb-3">
      <i class="pi pi-pencil mr-2"></i>{{ t("Task Notes") }}
    </h3>
    <Textarea
      :value="focusNotes"
      :placeholder="t('Add your task notes here...')"
      :autoResize="true"
      rows="3"
      class="w-full bg-white dark:bg-gray-700"
      @input="emit('update:focusNotes', $event.target.value)"
    />
  </div>
</template>