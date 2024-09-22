<script setup lang="ts">
import { ref, nextTick, onMounted, computed } from 'vue';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import { useClientsStore } from '@/stores/clients';
import InputText from 'primevue/inputtext';
import MainActionButton from '@/components/form/PrimaryActionButton.vue';
import ActionButton from '@/components/form/SecondaryActionButton.vue';
import TaskStatusCard from "@/components/TaskStatusCard.vue";
import UploadAvatar from "@/components/form/UploadAvatar.vue";
import Avatar from "primevue/avatar";
import AvatarGroup from "primevue/avatargroup";

const clientsStore = useClientsStore();
const emit = defineEmits(['save', 'discard']);

const client = ref({
  name: '',
  email: '',
  phone: '',
  avatar: '',
});

const nameInput = ref(null);

const emailRegex = /^\S+@\S+\.\S+$/;
const phoneRegex = /^\+?[0-9]{1,4}[0-9]{6,14}$/;

const nameError = ref('');
const emailError = ref('');
const phoneError = ref('');

const isNameInvalid = computed(() => !client.value.name);
const isEmailInvalid = computed(() => client.value.email && !emailRegex.test(client.value.email));
const isPhoneInvalid = computed(() => client.value.phone && !phoneRegex.test(client.value.phone));

const isValid = computed(() => {
  return client.value.name && !nameError.value && !emailError.value && !phoneError.value;
});

const focusNameInput = async () => {
  await nextTick();
  if (nameInput.value) {
    nameInput.value.$el.focus();
    nameInput.value.$el.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
};

onMounted(() => {
  focusNameInput();
});

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

const saveClient = async () => {
  if (isValid.value) {
    await clientsStore.create(client.value);
    emit('save');
  }
};

const discardClient = () => {
  emit('discard');
};

const updateAvatar = (avatar: string) => {
  client.value.avatar = avatar;
};

const handleKeyDown = (event: KeyboardEvent) => {
  if (event.key === 'Enter') {
    saveClient();
  }
};
</script>

<template>
  <div class="w-full bg-white shadow-lg rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
    <div class="bg-gradient-to-r from-indigo-400 to-indigo-600 p-6">
      <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
        <div class="flex items-center">
          <UploadAvatar
              v-if="!client.avatar || client.avatar === ''"
              @update:avatar="updateAvatar"
          />
          <AvatarGroup v-else>
            <Avatar
                class="mr-2 transition-transform duration-300 hover:scale-105"
                shape="circle"
                :image="client.avatar"
                size="xlarge"
                :alt="client.name"
            />
          </AvatarGroup>
          <div class="ml-4 text-white">
            <div class="flex flex-col gap-2">
              <InputText
                  ref="nameInput"
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
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto self-center sm:self-start">
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
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="grid grid-cols-2 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-6 flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ t("Revenue") }}</p>
            <span class="text-3xl font-bold text-blue-700">0 $</span>
          </div>
          <i class="pi pi-dollar text-5xl text-blue-300"></i>
        </div>
        <div class="bg-green-50 rounded-lg p-6 flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ t("Time Worked") }}</p>
            <span class="text-3xl font-bold text-green-700">0 {{ t("hours") }}</span>
          </div>
          <i class="pi pi-clock text-5xl text-green-300"></i>
        </div>
      </div>

      <h4 class="text-xl font-semibold text-gray-700 mb-4">{{ t("Task Overview") }}</h4>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <TaskStatusCard :count="0" :label="t('In Progress')" icon="pi-spin pi-spinner" color="orange" />
        <TaskStatusCard :count="0" :label="t('Todo')" icon="pi-list" color="yellow" />
        <TaskStatusCard :count="0" :label="t('Blocked')" icon="pi-ban" color="red" />
        <TaskStatusCard :count="0" :label="t('Completed')" icon="pi-check-circle" color="green" />
      </div>

      <div class="flex flex-col sm:flex-row justify-end gap-4">
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
      </div>
    </div>
  </div>
</template>
