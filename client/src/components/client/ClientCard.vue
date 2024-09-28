<script setup lang="ts">
import {ref, computed, nextTick, onMounted, watch} from 'vue';
import {useI18n} from 'vue-i18n';
import {useClientsStore} from '@/stores/clients';
import InputText from 'primevue/inputtext';
import PrimaryActionButton from '@/components/form/PrimaryActionButton.vue';
import SecondaryActionButton from '@/components/form/SecondaryActionButton.vue';
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import Tag from "primevue/tag";
import {defineProps, defineEmits} from 'vue';
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import type {Client} from '@/interfaces/Client';
import {useConfirm} from "primevue/useconfirm";
import {useToast} from "primevue/usetoast";
import Avatar from "@/components/form/Avatar.vue";
import TaskOverviewGrid from "@/components/report/TaskOverviewGrid.vue";
import TimeOverviewGrid from "@/components/report/TimeOverviewGrid.vue";
import RevenueOverviewGrid from "@/components/report/RevenueOverviewGrid.vue";
import clientToClientForm from "@/services/mappers/clientToClientForm";

const props = defineProps<{
  client: Client;
  isDraft: boolean;
}>();

const {t} = useI18n();
const confirm = useConfirm();
const toast = useToast();

const clientsStore = useClientsStore();
const emit = defineEmits(['save', 'discard']);

const isEditing = ref(props.isDraft);
const client = ref({...props.client});

const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const phoneRegex = /^\+?[0-9]{1,4}[-.\s]?[0-9]{3}[-.\s]?[0-9]{3,4}[-.\s]?[0-9]{3,4}$/;

const nameError = ref('');
const emailError = ref('');
const phoneError = ref('');

const isNameInvalid = computed(() => nameError.value !== '');
const isEmailInvalid = computed(() => emailError.value !== '');
const isPhoneInvalid = computed(() => phoneError.value !== '');

const isValid = computed(() => {
  return client.value.name && !nameError.value && !emailError.value && !phoneError.value;
});

const validateName = () => {
  if (!client.value.name || client.value.name === '') {
    nameError.value = t('Name cannot be empty');
  } else {
    nameError.value = '';
  }
};

const revalidateName = () => {
  if (nameError.value && nameError.value !== '') {
    validateName();
  }
};

const validateEmail = () => {
  if (client.value.email && client.value.email !== '' && !emailRegex.test(client.value.email)) {
    emailError.value = t('Invalid email format');
  } else {
    emailError.value = '';
  }
};

const revalidateEmail = () => {
  if (emailError.value && emailError.value !== '') {
    validateEmail();
  }
};

const validatePhone = () => {
  if (client.value.phone && !phoneRegex.test(client.value.phone)) {
    phoneError.value = t('Invalid phone format');
  } else {
    phoneError.value = '';
  }
};

const revalidatePhone = () => {
  if (phoneError.value && phoneError.value !== '') {
    validatePhone();
  }
};

const saveClient = async () => {
  validateName();
  validateEmail();
  validatePhone();
  if (isValid.value) {
    const clientPayload = clientToClientForm(client.value);
    if (props.isDraft) {
      await clientsStore.create(clientPayload);
      toast.add({severity: 'success', summary: t('Created'), detail: t('Client created'), life: 3000});
    } else {
      await clientsStore.update(client.value.id, clientPayload);
      toast.add({severity: 'success', summary: t('Saved'), detail: t('Client saved'), life: 3000});
    }
    emit('save');
    isEditing.value = false;
  }
};

const discardClient = () => {
  client.value = {...props.client}; // Reset client data
  isEditing.value = false; // Exit editing mode
  emit('discard');
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
      toast.add({severity: 'error', summary: t('Deleted'), detail: t('Client deleted'), life: 3000});
    }
  })
};

const deleteClient = async () => {
  try {
    await clientsStore.delete(client.value.id);
  } catch (e) {
    console.error(e);
  }
};

const handleKeyDown = (event: KeyboardEvent) => {
  if (event.key === 'Enter') {
    saveClient();
  }
};

watch(() => props.client, (newClient) => {
  client.value = {...newClient};
});
</script>

<template>
  <div
      class="w-full bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow">
    <div class="bg-gradient-to-r from-indigo-400 to-indigo-500 dark:from-indigo-600 dark:to-indigo-700 p-6">
      <div
          :class="['flex flex-col lg:flex-row items-center lg:items-center gap-4', { 'justify-center lg:justify-between': !client.email && !client.phone, 'justify-between': client.email || client.phone }]">
        <div class="flex flex-col lg:flex-row items-center lg:items-center w-full lg:w-auto">
          <div class="flex items-center w-full lg:w-auto">
            <div class="flex items-center relative">
              <Avatar
                  v-model:avatar-path="client.avatar"
                  :placeholder-icon="'pi pi-user'"
                  :is-editing="isEditing"
                  :alt="client.name"
              />
            </div>
            <div class="ml-4 text-white w-full lg:w-auto flex items-center">
              <div v-if="isEditing" class="flex flex-col gap-2 w-full">
                <label class="block text-sm font-medium text-white mb-1">{{ t("NAME") }}</label>
                <InputText
                    id="editNameInput"
                    v-model="client.name"
                    :placeholder="t('John Doe')"
                    class="w-full dark:bg-gray-700 dark:text-white"
                    :invalid="isNameInvalid"
                    aria-describedby="name-help"
                    @blur="validateName"
                    @update:model-value="revalidateName"
                    @keydown="handleKeyDown"
                />
                <Tag v-if="isNameInvalid" severity="danger" class="w-full" :value="nameError"/>
                <small id="name-help" class="text-white">{{ t("Name is required") }}</small>
              </div>
              <h3 v-else class="font-bold text-2xl">{{ client.name }}</h3>
              <p v-if="client.internal" class="text-blue-100 dark:text-blue-200">{{ t("You") }}</p>
            </div>
          </div>
        </div>
        <div
            v-if="isEditing || client.email || client.phone"
            class="flex flex-col lg:flex-row gap-2 items-center justify-center h-full w-full lg:w-auto">
          <template v-if="isEditing">
            <div class="flex flex-col gap-2 items-start justify-center h-full w-full">
              <label class="block text-sm font-medium text-white mb-1">{{ t("EMAIL") }}</label>
              <IconField class="w-full">
                <InputIcon class="pi pi-envelope"/>
                <InputText
                    v-model="client.email"
                    :placeholder="t('example@example.com')"
                    class="w-full dark:bg-gray-700 dark:text-white"
                    :invalid="isEmailInvalid"
                    aria-describedby="email-help"
                    @blur="validateEmail"
                    @update:model-value="revalidateEmail"
                />
              </IconField>
              <Tag v-if="isEmailInvalid" severity="danger" class="w-full" :value="emailError"/>
              <small id="email-help" class="text-white">{{ t('Format: example@example.com') }}</small>
            </div>
            <div class="flex flex-col gap-2 items-start justify-center h-full w-full">
              <label class="block text-sm font-medium text-white mb-1">{{ t("PHONE") }}</label>
              <IconField class="w-full">
                <InputIcon class="pi pi-phone"/>
                <InputText
                    v-model="client.phone"
                    :placeholder="t('+123-456-7890')"
                    class="w-full dark:bg-gray-700 dark:text-white"
                    :invalid="isPhoneInvalid"
                    aria-describedby="phone-help"
                    @blur="validatePhone"
                    @update:model-value="revalidatePhone"
                />
              </IconField>
              <Tag v-if="isPhoneInvalid" severity="danger" class="w-full" :value="phoneError"/>
              <small id="phone-help" class="text-white">{{ t('Expected format: +123-456-7890') }}</small>
            </div>
          </template>
          <template v-else>
            <div class="flex flex-col gap-2 items-end justify-center h-full w-full lg:w-auto">
              <a
                  v-if="client.email" :href="`mailto:${client.email}`"
                  class="bg-white dark:bg-gray-700 bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full lg:w-auto">
                <i class="pi pi-envelope mr-2"></i>
                <span class="text-sm">{{ client.email }}</span>
              </a>
            </div>
            <div class="flex flex-col gap-2 items-end justify-center h-full w-full lg:w-auto">
              <a
                  v-if="client.phone" :href="`tel:${client.phone}`"
                  class="bg-white dark:bg-gray-700 bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full lg:w-auto">
                <i class="pi pi-phone mr-2"></i>
                <span class="text-sm">{{ client.phone }}</span>
              </a>
            </div>
          </template>
        </div>
      </div>
    </div>

    <div class="p-6">
      <template v-if="!isEditing">
        <TimeOverviewGrid
            :time-worked="client.timeWorked"
            :time-estimated="client.timeEstimated"
        />
        <RevenueOverviewGrid
            :income="client.income"
            :expenses="client.expenses"
            :revenue="client.revenue"
            :invoiced="client.invoiced"
            :paid="client.paid"
            :estimated="client.incomeEstimated"
        />
        <TaskOverviewGrid
            :in-progress-tasks="client.inProgressTasks"
            :todo-tasks="client.todoTasks"
            :blocked-tasks="client.blockedTasks"
            :completed-tasks="client.completedTasks"
        />
      </template>
      <div class="flex flex-col sm:flex-row justify-end gap-4">
        <template v-if="isEditing">
          <SecondaryActionButton @click="discardClient">
            <i class="pi pi-times mr-2"></i>
            {{ t("Discard") }}
          </SecondaryActionButton>
          <PrimaryActionButton
              :disabled="!isValid"
              @click="saveClient"
          >
            <i class="pi pi-check mr-2"></i>
            {{ t("Save Client") }}
          </PrimaryActionButton>
        </template>
        <template v-else>
          <SecondaryActionButton @click="isEditing = true">
            <i class="pi pi-pencil mr-2"></i>
            {{ t("Edit") }}
          </SecondaryActionButton>
          <DestructiveActionButton v-if="!client.internal" @click="confirmDeletion">
            <i class="pi pi-trash mr-2"></i>
            {{ t("Delete") }}
          </DestructiveActionButton>
        </template>
      </div>
    </div>
  </div>
</template>
