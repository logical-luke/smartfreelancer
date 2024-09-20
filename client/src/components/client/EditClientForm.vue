<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useClientsStore } from '@/stores/clients';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import MainActionButton from '@/components/form/MainActionButton.vue';
import ActionButton from '@/components/form/ActionButton.vue';
import ImageUploadInput from '@/components/form/ImageUploadInput.vue';

const clientsStore = useClientsStore();
const router = useRouter();
const route = useRoute();
const client = ref({
  id: null,
  name: '',
  avatar: '',
  email: '',
  phone: '',
});
const cancelPageRoute = ref({ name: 'ClientsPage' });
const afterSavePageRoute = ref({ name: 'ClientsPage' });
const phoneValid = ref(true);
const emailValid = ref(true);
const nameValid = ref(true);

const hasAvatar = () => client.value.avatar !== '' && client.value.avatar !== null;
const updateAvatar = (avatar: string) => { client.value.avatar = avatar; };
const clearAvatar = () => { client.value.avatar = ''; };

const updateEmailValid = () => {
  const emailRegex = /^\S+@\S+\.\S+$/;
  emailValid.value = client.value.email.length === 0 || emailRegex.test(client.value.email);
};

const updatePhoneValid = () => {
  const phoneRegex = /^\+?[0-9]{1,4}[0-9]{6,14}$/;
  phoneValid.value = client.value.phone.length === 0 || phoneRegex.test(client.value.phone);
};

const updateNameValid = () => {
  nameValid.value = client.value.name.length > 0;
};

const canSubmitForm = () => {
  return nameValid.value && emailValid.value && phoneValid.value;
};

const submitForm = async () => {
  if (!canSubmitForm() || client.value.name.length === 0) {
    return;
  }

  await clientsStore.update(client.value);
  router.push(afterSavePageRoute.value);
};

onMounted(async () => {
  await clientsStore.load();
  const clientObject = clientsStore.clients.find(c => c.id === route.params.id);
  if (!clientObject) {
    await router.push({ name: 'NotFoundPage' });
  }
  client.value = clientObject;
});
</script>

<template>
  <div @keyup.enter="submitForm">
    <label class="flex flex-col text-sm font-semibold gap-2" for="name">
      <span> {{ t("Name") }} <span class="text-red-500">*</span> </span>
      <InputText
        id="name"
        v-model="client.name"
        class="block w-full md:w-1/2 px-4 py-4 text-sm placeholder-gray-500 bg-white border rounded"
        name="name"
        placeholder="John Doe"
        :invalid="!nameValid"
        @focusout="updateNameValid"
      />
    </label>
  </div>

  <div class="w-full md:w-1/2" @keyup.enter="submitForm">
    <label class="flex flex-col gap-2 text-sm font-semibold" for="email">
      {{ t("Email") }}
      <InputGroup>
        <InputGroupAddon></InputGroupAddon>
        <InputText
          id="email"
          v-model="client.email"
          name="email"
          placeholder="john.doe@domain.com"
          :invalid="!emailValid"
          @focusout="updateEmailValid"
        />
      </InputGroup>
    </label>
  </div>

  <div class="w-full md:w-1/2" @keyup.enter="submitForm">
    <label class="flex flex-col gap-2 text-sm font-semibold" for="phone">
      {{ t("Phone") }}
      <InputGroup>
        <InputGroupAddon></InputGroupAddon>
        <InputText
          id="phone"
          v-model="client.phone"
          name="phone"
          placeholder="+1 561-555-7689"
          :invalid="!phoneValid"
          @focusout="updatePhoneValid"
        />
      </InputGroup>
    </label>
  </div>

  <div class="w-full md:w-1/2" @keyup.enter="submitForm">
    <label class="flex flex-col gap-2 text-sm font-semibold" for="photo">
      {{ t("Photo") }}
      <ImageUploadInput
        v-if="!hasAvatar()"
        @file-uploaded="updateAvatar"
      />
      <span v-else class="flex flex-row items-center gap-2">
        <img :src="client.avatar" alt="avatar" class="w-20 h-20 rounded-full" />
      </span>
    </label>
  </div>

  <div class="flex gap-4 flex-col md:flex-row justify-center md:justify-start w-full md:w-1/2">
    <MainActionButton
      :disabled="!canSubmitForm() || client.name.length === 0"
      class="w-full md:w-auto"
      @keyup.enter="submitForm"
      @click="submitForm"
    >
      {{ t("Save") }}
    </MainActionButton>
    <router-link :to="cancelPageRoute">
      <ActionButton class="w-full md:w-auto">{{ t("Cancel") }}</ActionButton>
    </router-link>
  </div>
</template>
