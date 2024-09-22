<script setup lang="ts">
import {ref, computed, nextTick, onMounted, watch} from 'vue';
import {useI18n} from 'vue-i18n';
import {useClientsStore} from '@/stores/clients';
import InputText from 'primevue/inputtext';
import MainActionButton from '@/components/form/PrimaryActionButton.vue';
import ActionButton from '@/components/form/SecondaryActionButton.vue';
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import TaskStatusCard from "@/components/task/TaskStatusCard.vue";
import UploadAvatar from "@/components/form/UploadAvatar.vue";
import {defineProps, defineEmits} from 'vue';
import Avatar from "primevue/avatar";
import AvatarGroup from "primevue/avatargroup";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import type Client from '@/interfaces/client';
import {useConfirm} from "primevue/useconfirm";
import {useToast} from "primevue/usetoast";

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

const emailRegex = /^\S+@\S+\.\S+$/;
const phoneRegex = /^\+?[0-9]{1,4}[0-9]{6,14}$/;

const nameError = ref('');
const emailError = ref('');
const phoneError = ref('');

const isNameInvalid = computed(() => nameError.value !== '');
const isEmailInvalid = computed(() => emailError.value !== '');
const isPhoneInvalid = computed(() => phoneError.value !== '');

const isValid = computed(() => {
  return client.value.name && !nameError.value && !emailError.value && !phoneError.value;
});

const focusNameInput = async () => {
  await nextTick();
  const nameInput = document.getElementById('editNameInput');
  if (nameInput) nameInput.focus();
};

onMounted(() => {
  if (props.isDraft) focusNameInput();
});

const validateName = () => {
  console.log('validateName');
  if (!client.value.name || client.value.name === '') {
    nameError.value = t('Name is required');
  } else {
    nameError.value = '';
  }
};

const revalidateName = () => {
  console.log('revalidateName');
  if (nameError.value && nameError.value !== '') {
    validateName();
  }
};

const validateEmail = () => {
  console.log('validateEmail');
  if (client.value.email && client.value.email !== '' && !emailRegex.test(client.value.email)) {
    emailError.value = t('Invalid email format');
  } else {
    emailError.value = '';
  }
};

const revalidateEmail = () => {
  console.log('revalidateEmail');
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
  if (isValid.value) {
    if (props.isDraft) {
      await clientsStore.create(client.value);
      emit('save');
    } else {
      await clientsStore.update(client.value.id, client.value);
      isEditing.value = false;
    }
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
      toast.add({severity: 'info', summary: t('Deleted'), detail: t('Client deleted'), life: 3000});
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

const updateAvatar = (avatar: string) => {
  client.value.avatar = avatar;
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
  <div class="w-full bg-white shadow rounded-lg overflow-hidden transition-all duration-300 hover:shadow">
    <div class="bg-gradient-to-r from-indigo-400 to-indigo-500 p-6">
      <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
        <div class="flex flex-col lg:flex-row items-center">
          <UploadAvatar
              v-if="isEditing && (!client.avatar || client.avatar === '')"
              @update:avatar="updateAvatar"
              class="mb-4 lg:mb-0"
          />
          <AvatarGroup v-else class="mb-4 lg:mb-0">
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
          <div class="ml-0 lg:ml-4 text-white">
            <div v-if="isEditing" class="flex flex-col gap-2">
              <InputText
                  id="editNameInput"
                  v-model="client.name"
                  :placeholder="t('John Doe')"
                  class="text-2xl font-bold w-full"
                  :invalid="isNameInvalid"
                  @blur="validateName"
                  @update:model-value="revalidateName"
                  @keydown="handleKeyDown"
              />
              <small id="name-help" class="text-white" v-if="isNameInvalid">{{ t('Name is required') }}</small>
            </div>
            <h3 v-else class="font-bold text-2xl">{{ client.name }}</h3>
            <p v-if="client.internal" class="text-blue-100">{{ t("You") }}</p>
          </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-2 items-center justify-center h-full">
          <template v-if="isEditing">
            <div class="flex flex-col gap-2 items-center justify-center h-full w-full lg:w-auto">
              <IconField>
                <InputIcon class="pi pi-envelope"/>
                <InputText
                    v-model="client.email"
                    placeholder="john.doe@company.com"
                    class="rounded-full transition-colors duration-300 w-full lg:w-auto"
                    :invalid="isEmailInvalid"
                    @blur="validateEmail"
                    @update:model-value="revalidateEmail"
                    @keydown="handleKeyDown"
                />
              </IconField>
              <small id="email-help" class="text-white" v-if="isEmailInvalid">{{ t('Invalid email format') }}</small>
            </div>
            <div class="flex flex-col gap-2 items-center justify-center h-full w-full lg:w-auto">
              <IconField>
                <InputIcon class="pi pi-phone"/>
                <InputText
                    v-model="client.phone"
                    placeholder="+48 123 456 789"
                    class="rounded-full transition-colors duration-300 w-full lg:w-auto"
                    :invalid="isPhoneInvalid"
                    @blur="validatePhone"
                    @update:model-value="revalidatePhone"
                    @keydown="handleKeyDown"
                />
              </IconField>
              <small id="phone-help" class="text-white" v-if="isPhoneInvalid">{{ t('Invalid phone format') }}</small>
            </div>
          </template>
          <template v-else>
            <a v-if="client.email" :href="`mailto:${client.email}`"
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full lg:w-auto">
              <i class="pi pi-envelope mr-2"></i>
              <span class="text-sm">{{ client.email }}</span>
            </a>
            <a v-if="client.phone" :href="`tel:${client.phone}`"
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-full flex items-center transition-colors duration-300 w-full lg:w-auto">
              <i class="pi pi-phone mr-2"></i>
              <span class="text-sm">{{ client.phone }}</span>
            </a>
          </template>
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div v-if="!client.internal"
             class="bg-blue-50 rounded-lg p-6 flex items-center justify-between transition-all duration-300 hover:shadow">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ t("Revenue") }}</p>
            <span class="text-3xl font-bold text-blue-700">{{ client.revenue }} $</span>
          </div>
          <i class="pi pi-dollar text-5xl text-blue-300"></i>
        </div>
        <div
            class="bg-green-50 rounded-lg p-6 flex items-center justify-between transition-all duration-300 hover:shadow">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ t("Time Worked") }}</p>
            <span class="text-3xl font-bold text-green-700">{{ client.timeWorked }} {{ t("hours") }}</span>
          </div>
          <i class="pi pi-clock text-5xl text-green-300"></i>
        </div>
      </div>

      <h4 class="text-xl font-semibold text-gray-700 mb-4">{{ t("Task Overview") }}</h4>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <TaskStatusCard :count="client.inProgressTasks" :label="t('In Progress')" icon="pi-spin pi-spinner"
                        color="orange"/>
        <TaskStatusCard :count="client.todoTasks" :label="t('Todo')" icon="pi-list" color="yellow"/>
        <TaskStatusCard :count="client.blockedTasks" :label="t('Blocked')" icon="pi-ban" color="red"/>
        <TaskStatusCard :count="client.completedTasks" :label="t('Completed')" icon="pi-check-circle" color="green"/>
      </div>

      <div class="flex flex-col sm:flex-row justify-end gap-4">
        <template v-if="isEditing">
          <ActionButton @click="discardClient">
            <i class="pi pi-times mr-2"></i>
            {{ t("Discard") }}
          </ActionButton>
          <MainActionButton
              :disabled="!isValid"
              @click="saveClient"
          >
            <i class="pi pi-check mr-2"></i>
            {{ t("Save Client") }}
          </MainActionButton>
        </template>
        <template v-else>
          <ActionButton @click="isEditing = true">
            <i class="pi pi-pencil mr-2"></i>
            {{ t("Edit") }}
          </ActionButton>
          <DestructiveActionButton v-if="!client.internal" @click="confirmDeletion">
            <i class="pi pi-trash mr-2"></i>
            {{ t("Delete") }}
          </DestructiveActionButton>
        </template>
      </div>
    </div>
  </div>
</template>
