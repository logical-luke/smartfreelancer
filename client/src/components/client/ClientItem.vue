<script setup lang="ts">
import {useI18n} from "vue-i18n";
const { t } = useI18n();
import { useRouter } from 'vue-router';
import { useClientsStore } from '@/stores/clients';
import ActionButton from '@/components/form/ActionButton.vue';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const props = defineProps<{
  id: string;
  name: string;
  email?: string;
  phone?: string;
  avatar?: string;
  revenue?: number;
  timeWorked?: number;
  ongoingTasks?: number;
  plannedTasks?: number;
  finishedTasks?: number;
  blockedTasks?: number;
}>();

const router = useRouter();
const clientsStore = useClientsStore();
const confirm = useConfirm();
const toast = useToast();

const hasPhone = () => !!props.phone;
const hasEmail = () => !!props.email;
const getAvatar = () => (props.avatar && props.avatar !== '' ? props.avatar : '/client-placeholder.png');

const goToEditClientPage = async () => {
  await router.push({ name: 'EditClientPage', params: { id: props.id } });
};

const confirmDeletion = async () => {
  confirm.require({
    message: 'Do you want to delete this client?',
    header: 'Danger Zone',
    icon: 'pi pi-info-circle',
    rejectLabel: 'Cancel',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: 'Delete',
      severity: 'danger'
    },
    accept: () => {
      deleteClient();
      toast.add({ severity: 'info', summary: 'Deleted', detail: 'Client deleted', life: 3000 });
    }
  })
};

const deleteClient = async () => {
  try {
    await clientsStore.delete(props.id);
  } catch (e) {
    console.error(e);
  }
};
</script>

<template>
  <div class="p-8 w-full bg-white shadow rounded">
    <div class="flex justify-between items-center mb-8">
      <div class="flex items-center">
        <img
          class="w-20 h-20 p-1 mr-4 rounded-full border border-indigo-700"
          :src="getAvatar()"
          :alt="name"
        />
        <div>
          <h3 class="font-medium text-lg">{{ name }}</h3>
        </div>
      </div>
    </div>

    <div v-if="hasPhone() || hasEmail()" class="mb-8 p-4 bg-gray-100 rounded">
      <div
        v-if="hasEmail()"
        :class="hasPhone() ? 'mb-4' : ''"
        class="flex items-center gap-4"
      >
        <span>{{ t("Email") }}: {{ email }}</span>
      </div>
      <div v-if="hasPhone()" class="flex items-center gap-4">
        <span>{{ t("Phone") }}: {{ phone }}</span>
      </div>
    </div>

    <div class="flex flex-col md:flex-row mb-8 gap-4">
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/2">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ t("Revenue") }}</p>
        </div>
        <span>{{ revenue }} $</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/2">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ t("Time Worked") }}</p>
        </div>
        <span>{{ timeWorked }} {{ t("hours") }}</span>
      </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4 mb-8">
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ t("Ongoing Tasks") }}</p>
        </div>
        <span>{{ ongoingTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ t("Planned Tasks") }}</p>
        </div>
        <span>{{ plannedTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ t("Finished Tasks") }}</p>
        </div>
        <span>{{ finishedTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ t("Blocked Tasks") }}</p>
        </div>
        <span>{{ blockedTasks }}</span>
      </div>
    </div>

    <div class="flex gap-4 flex-col items-center md:flex-row">
      <ActionButton @click="goToEditClientPage">{{
        t("Edit")
      }}</ActionButton>
      <ActionButton @click="confirmDeletion">{{ t("Delete") }}</ActionButton>
    </div>
  </div>
</template>
