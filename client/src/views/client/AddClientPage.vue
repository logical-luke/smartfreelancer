<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import { ref } from 'vue';
import AddClientForm from '@/components/client/AddClientForm.vue';

const title = ref('Add Client');
</script>

<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ t(title) }}</h3>
    </div>
  </div>
  <div class="flex container">
    <div class="flex flex-col gap-4 w-full">
      <AddClientForm />
    </div>
  </div>
</template>
