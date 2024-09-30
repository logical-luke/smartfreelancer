<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import PrimaryActionButton from "@/components/form/PrimaryActionButton.vue";
import SecondaryActionButton from "@/components/form/SecondaryActionButton.vue";
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import { ref } from 'vue';

const automatedActions = ref([
  { id: 1, name: 'Send Welcome Email', status: 'Active' },
  { id: 2, name: 'Generate Monthly Report', status: 'Inactive' },
  { id: 3, name: 'Backup Database', status: 'Active' }
]);

function toggleActionStatus(actionId) {
  const action = automatedActions.value.find(a => a.id === actionId);
  if (action) {
    action.status = action.status === 'Active' ? 'Inactive' : 'Active';
  }
}

function deleteAction(actionId) {
  automatedActions.value = automatedActions.value.filter(a => a.id !== actionId);
}
</script>

<template>
  <PageHeader title="Automations" icon="pi-cog" />
  <div class="container mt-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
        <i class="pi pi-list mr-2"></i>Automated Actions
      </h3>
      <div v-for="action in automatedActions" :key="action.id" class="flex justify-between items-center mb-4 p-2 border-b border-gray-200 dark:border-gray-700">
        <div>
          <p class="text-gray-800 dark:text-gray-200">{{ action.name }}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ action.status }}</p>
        </div>
        <div class="flex space-x-2">
          <PrimaryActionButton @click="toggleActionStatus(action.id)">
            {{ action.status === 'Active' ? 'Deactivate' : 'Activate' }}
          </PrimaryActionButton>
          <DestructiveActionButton @click="deleteAction(action.id)">
            Delete
          </DestructiveActionButton>
        </div>
      </div>
    </div>
    <AddItemFloatingButton @click="addDraftAutomation" />
  </div>
</template>

<style scoped>
.container {
  @apply mt-4;
}
</style>