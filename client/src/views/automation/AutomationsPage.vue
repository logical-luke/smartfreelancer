<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import SectionWrapper from "@/components/SectionWrapper.vue";
import AddItemFloatingButton from "@/components/navigation/AddItemFloatingButton.vue";
import { ref } from 'vue';
import ActionButton from "@/components/form/ActionButton.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';

const automatedActions = ref([
  { id: 1, name: 'Send Welcome Email', status: 'Active', trigger: 'New User Registration', lastRun: '2023-10-01' },
  { id: 2, name: 'Generate Monthly Report', status: 'Inactive', trigger: 'Monthly', lastRun: '2023-09-30' },
  { id: 3, name: 'Backup Database', status: 'Active', trigger: 'Daily', lastRun: '2023-10-05' },
  { id: 4, name: 'Clean Up Temporary Files', status: 'Active', trigger: 'Weekly', lastRun: '2023-10-02' },
  { id: 5, name: 'Send Payment Reminders', status: 'Active', trigger: 'Invoice Overdue', lastRun: '2023-10-04' },
]);

function toggleActionStatus(action) {
  action.status = action.status === 'Active' ? 'Inactive' : 'Active';
}

function deleteAction(actionId) {
  automatedActions.value = automatedActions.value.filter(a => a.id !== actionId);
}

function addDraftAutomation() {
  // Implement add automation logic here
  console.log('Adding new automation');
}

const getStatusSeverity = (status) => {
  return status === 'Active' ? 'success' : 'danger';
};
</script>

<template>
  <PageHeader title="Automations" icon="pi-cog" />
  <div class="container mt-4">
    <SectionWrapper title="Automated Actions" icon="pi pi-list" color="indigo">
      <DataTable :value="automatedActions" class="p-datatable-sm" responsiveLayout="scroll">
        <Column field="name" header="Action Name"></Column>
        <Column field="trigger" header="Trigger"></Column>
        <Column field="lastRun" header="Last Run"></Column>
        <Column field="status" header="Status">
          <template #body="slotProps">
            <Tag :value="slotProps.data.status" :severity="getStatusSeverity(slotProps.data.status)" />
          </template>
        </Column>
        <Column header="Actions">
          <template #body="slotProps">
            <ActionButton @click="toggleActionStatus(slotProps.data)" :class="slotProps.data.status === 'Active' ? 'p-button-warning' : 'p-button-success'">
              {{ slotProps.data.status === 'Active' ? 'Deactivate' : 'Activate' }}
            </ActionButton>
            <ActionButton class="ml-2" severity="danger" @click="deleteAction(slotProps.data.id)">
              Delete
            </ActionButton>
          </template>
        </Column>
      </DataTable>
    </SectionWrapper>
    <AddItemFloatingButton @click="addDraftAutomation" />
  </div>
</template>