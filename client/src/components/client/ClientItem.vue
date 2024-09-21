<script setup lang="ts">
import {useI18n} from "vue-i18n";
const { t } = useI18n();
import { useRouter } from 'vue-router';
import { useClientsStore } from '@/stores/clients';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import SecondaryActionButton from "@/components/form/SecondaryActionButton.vue";
import TaskStatusCard from "@/components/TaskStatusCard.vue";

const props = defineProps<{
  id: string;
  name: string;
  email: string|null;
  phone: string|null;
  avatar: string|null;
  revenue: number;
  timeWorked: number;
  todoTasks: number;
  inProgressTasks: number;
  blockedTasks: number;
  completedTasks: number;
  internal: boolean;
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
    message: t(`This action cannot be undone.`),
    header: t('Delete client?'),
    icon: 'pi pi-info-circle',
    rejectProps: {
      label: t('Cancel'),
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: t('Delete'),
      severity: 'danger'
    },
    accept: () => {
      deleteClient();
      toast.add({ severity: 'info', summary: t('Deleted'), detail: t('Client deleted'), life: 3000 });
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
  <div class="w-full bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center">
          <img
              class="w-20 h-20 rounded-full border-4 border-white shadow-lg transition-transform duration-300 hover:scale-105"
              :src="getAvatar()"
              :alt="name"
          />
          <div class="ml-4 text-white">
            <h3 class="font-bold text-2xl">{{ name }}</h3>
            <p v-if="internal" class="text-blue-100">{{ t("You") }}</p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
          <a v-if="hasEmail()" :href="`mailto:${email}`" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300">
            <i class="pi pi-envelope mr-2"></i>
            <span class="text-sm">{{ email }}</span>
          </a>
          <a v-if="hasPhone()" :href="`tel:${phone}`" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300">
            <i class="pi pi-phone mr-2"></i>
            <span class="text-sm">{{ phone }}</span>
          </a>
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div v-if="!internal" class="bg-blue-50 rounded-lg p-6 flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ t("Revenue") }}</p>
            <span class="text-3xl font-bold text-blue-700">{{ revenue }} $</span>
          </div>
          <i class="pi pi-dollar text-5xl text-blue-300"></i>
        </div>
        <div class="bg-green-50 rounded-lg p-6 flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ t("Time Worked") }}</p>
            <span class="text-3xl font-bold text-green-700">{{ timeWorked }} {{ t("hours") }}</span>
          </div>
          <i class="pi pi-clock text-5xl text-green-300"></i>
        </div>
      </div>

      <h4 class="text-xl font-semibold text-gray-700 mb-4">{{ t("Task Overview") }}</h4>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <TaskStatusCard :count="inProgressTasks" :label="t('In Progress')" icon="pi-spin pi-spinner" color="orange" />
        <TaskStatusCard :count="todoTasks" :label="t('Todo')" icon="pi-list" color="yellow" />
        <TaskStatusCard :count="blockedTasks" :label="t('Blocked')" icon="pi-ban" color="red" />
        <TaskStatusCard :count="completedTasks" :label="t('Completed')" icon="pi-check-circle" color="green" />
      </div>

      <div class="flex flex-col sm:flex-row justify-end gap-4">
        <SecondaryActionButton @click="goToEditClientPage" class="w-full sm:w-auto">
          <i class="pi pi-pencil mr-2"></i>
          {{ t("Edit") }}
        </SecondaryActionButton>
        <DestructiveActionButton v-if="!internal" @click="confirmDeletion" class="w-full sm:w-auto">
          <i class="pi pi-trash mr-2"></i>
          {{ t("Delete") }}
        </DestructiveActionButton>
      </div>
    </div>
  </div>
</template>
