<script setup lang="ts">
import {ref, computed, nextTick} from 'vue';
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useRouter } from 'vue-router';
import { useClientsStore } from '@/stores/clients';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import SecondaryActionButton from "@/components/form/SecondaryActionButton.vue";
import PrimaryActionButton from "@/components/form/PrimaryActionButton.vue";
import TaskStatusCard from "@/components/TaskStatusCard.vue";
import Avatar from "primevue/avatar";
import AvatarGroup from "primevue/avatargroup";
import InputText from 'primevue/inputtext';
import UploadAvatar from "@/components/form/UploadAvatar.vue";

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

const clientsStore = useClientsStore();
const confirm = useConfirm();
const toast = useToast();

const isEditing = ref(false);
const client = ref({
  name: props.name,
  email: props.email,
  phone: props.phone,
  avatar: props.avatar,
});

const emailRegex = /^\S+@\S+\.\S+$/;
const phoneRegex = /^\+?[0-9]{1,4}[0-9]{6,14}$/;
const nameError = ref('');
const emailError = ref('');
const phoneError = ref('');

const isValid = computed(() => {
  if (!client.value.name) return false;
  if (client.value.email && !emailRegex.test(client.value.email)) return false;
  return !(client.value.phone && !phoneRegex.test(client.value.phone));
});

const hasPhone = computed(() => !!client.value.phone);
const hasEmail = computed(() => !!client.value.email);

const isNameInvalid = computed(() => !client.value.name);
const isEmailInvalid = computed(() => client.value.email && !emailRegex.test(client.value.email));
const isPhoneInvalid = computed(() => client.value.phone && !phoneRegex.test(client.value.phone));

const validateName = () => {
  if (!client.value.name) {
    nameError.value = t('Name is required');
  } else {
    nameError.value = '';
  }
};

const validateEmail = () => {
  if (client.value.email && !emailRegex.test(client.value.email)) {
    emailError.value = t('Invalid email format');
  } else {
    emailError.value = '';
  }
};

const validatePhone = () => {
  if (client.value.phone && !phoneRegex.test(client.value.phone)) {
    phoneError.value = t('Invalid phone format');
  } else {
    phoneError.value = '';
  }
};

const toggleEdit = async () => {
  isEditing.value = !isEditing.value;
  if (isEditing.value) {
    await nextTick();
    const nameInput = document.getElementById('editNameInput');
    if (nameInput) nameInput.focus();
  } else {
    // Reset error messages when canceling edit
    nameError.value = '';
    emailError.value = '';
    phoneError.value = '';
  }
};

const handleKeyDown = (event: KeyboardEvent) => {
  if (event.key === 'Enter') {
    saveChanges();
  }
};

const saveChanges = async () => {
  if (isValid.value) {
    try {
      await clientsStore.update(props.id, client.value);
      isEditing.value = false;
      toast.add({ severity: 'success', summary: t('Updated'), detail: t('Client updated'), life: 3000 });
    } catch (e) {
      console.error(e);
      toast.add({ severity: 'error', summary: t('Error'), detail: t('Failed to update client'), life: 3000 });
    }
  }
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

const updateAvatar = (avatar: string) => {
  client.value.avatar = avatar;
};
</script>

<template>
  <div class="w-full bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
    <div class="bg-gradient-to-r from-indigo-400 to-indigo-600 p-6">
      <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
        <div class="flex items-center">
          <UploadAvatar
              v-if="isEditing && (!client.avatar || client.avatar === '')"
              @update:avatar="updateAvatar"
          />
          <AvatarGroup v-else>
            <Avatar
                v-if="!client.avatar || client.avatar === ''"
                icon="pi pi-user"
                class="mr-2 transition-transform duration-300 hover:scale-105"
                shape="circle"
                size="xlarge"
                :alt="client.name"
            />
            <Avatar
                v-else
                class="mr-2 transition-transform duration-300 hover:scale-105"
                shape="circle"
                :image="client.avatar"
                size="xlarge"
                :alt="client.name"
            />
          </AvatarGroup>
          <div class="ml-4 text-white">
            <div v-if="isEditing" class="flex flex-col gap-2">
              <InputText
                  id="editNameInput"
                  v-model="client.name"
                  :placeholder="t('Client Name')"
                  class="text-2xl text-white font-bold bg-transparent border-b border-white placeholder-white focus:outline-none focus:border-white w-full"
                  :invalid="isNameInvalid"
                  aria-describedby="name-help"
                  @blur="validateName"
                  @keydown="handleKeyDown"
              />
              <small id="name-help" class="text-white" v-if="isNameInvalid">{{ t('Name is required') }}</small>
            </div>
            <h3 v-else class="font-bold text-2xl">{{ client.name }}</h3>
            <p v-if="internal" class="text-blue-100">{{ t("You") }}</p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 self-center sm:self-start">
          <template v-if="isEditing">
            <div class="flex flex-col gap-2">
              <InputText
                  v-model="client.email"
                  :placeholder="t('Email')"
                  class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full transition-colors duration-300 placeholder-white w-full sm:w-auto"
                  :invalid="isEmailInvalid"
                  aria-describedby="email-help"
                  @blur="validateEmail"
                  @keydown="handleKeyDown"
              />
              <small id="email-help" class="text-white" v-if="isEmailInvalid">{{ t('Invalid email format') }}</small>
            </div>
            <div class="flex flex-col gap-2">
              <InputText
                  v-model="client.phone"
                  :placeholder="t('Phone')"
                  class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full transition-colors duration-300 placeholder-white w-full sm:w-auto"
                  :invalid="isPhoneInvalid"
                  aria-describedby="phone-help"
                  @blur="validatePhone"
                  @keydown="handleKeyDown"
              />
              <small id="phone-help" class="text-white" v-if="isPhoneInvalid">{{ t('Invalid phone format') }}</small>
            </div>
          </template>
          <template v-else>
            <a v-if="hasEmail" :href="`mailto:${client.email}`" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300">
              <i class="pi pi-envelope mr-2"></i>
              <span class="text-sm">{{ client.email }}</span>
            </a>
            <a v-if="hasPhone" :href="`tel:${client.phone}`" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300">
              <i class="pi pi-phone mr-2"></i>
              <span class="text-sm">{{ client.phone }}</span>
            </a>
          </template>
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
        <template v-if="isEditing">
          <SecondaryActionButton @click="toggleEdit">
            <i class="pi pi-times mr-2"></i>
            {{ t("Cancel") }}
          </SecondaryActionButton>
          <PrimaryActionButton
              :disabled="!isValid"
              @click="saveChanges"
          >
            <i class="pi pi-check mr-2"></i>
            {{ t("Save Changes") }}
          </PrimaryActionButton>
        </template>
        <template v-else>
          <SecondaryActionButton @click="toggleEdit">
            <i class="pi pi-pencil mr-2"></i>
            {{ t("Edit") }}
          </SecondaryActionButton>
          <DestructiveActionButton v-if="!internal" @click="confirmDeletion">
            <i class="pi pi-trash mr-2"></i>
            {{ t("Delete") }}
          </DestructiveActionButton>
        </template>
      </div>
    </div>
  </div>
</template>
