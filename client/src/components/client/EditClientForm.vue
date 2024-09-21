<script setup lang="ts">
import {useI18n} from 'vue-i18n';

const {t} = useI18n();
import {ref, onMounted, type Ref} from 'vue';
import {useRouter, useRoute} from 'vue-router';
import {useClientsStore} from '@/stores/clients';
import InputText from 'primevue/inputtext';
import MainActionButton from '@/components/form/PrimaryActionButton.vue';
import ActionButton from '@/components/form/SecondaryActionButton.vue';
import ImageUploadInput from '@/components/form/ImageUploadInput.vue';
import type ClientForm from "@/interfaces/clientForm";
import api from "@/services/api";
import type Client from "@/interfaces/client";

const clientsStore = useClientsStore();
const router = useRouter();
const route = useRoute();
const id = String(route.params.id) as string;
const client: Ref<ClientForm> = ref({
  name: '',
  avatar: '',
  email: '',
  phone: '',
});
const cancelPageRoute = ref({name: 'ClientsPage'});
const afterSavePageRoute = ref({name: 'ClientsPage'});
const phoneValid = ref(true);
const emailValid = ref(true);
const nameValid = ref(true);

const hasAvatar = () => client.value.avatar !== '' && client.value.avatar !== null;
const updateAvatar = (avatar: string) => {
  client.value.avatar = avatar;
};
const clearAvatar = () => {
  client.value.avatar = '';
};

const updateEmailValid = () => {
  const emailRegex = /^\S+@\S+\.\S+$/;
  emailValid.value = client.value.email === null || client.value.email.length === 0 || emailRegex.test(client.value.email);
};

const updatePhoneValid = () => {
  const phoneRegex = /^\+?[0-9]{1,4}[0-9]{6,14}$/;
  phoneValid.value = client.value.phone === null || client.value.phone.length === 0 || phoneRegex.test(client.value.phone);
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

  console.log(id);

  await clientsStore.update(id, client.value);
  await router.push(afterSavePageRoute.value);
};

const clientToClientForm = (client: Client): ClientForm => {
  return {
    name: client.name,
    avatar: client.avatar,
    email: client.email,
    phone: client.phone,
  };
};

onMounted(async () => {
  if (!id || id === '') {
    return router.push({name: 'NotFoundPage'});
  }
  const clientObject = await api.clients.get(id);
  if (!clientObject) {
    return await router.push({name: 'NotFoundPage'});
  }
  client.value = clientToClientForm(clientObject);
});
</script>

<template>
  <div>
    <div class="w-full md:w-1/2 mb-4" @keyup.enter="submitForm">
      <label class="flex flex-col font-semibold gap-2" for="name">
        <span> {{ t("Name") }} <span class="text-red-500">*</span> </span>
        <InputText
            id="name"
            v-model="client.name"
            name="name"
            placeholder="John Doe"
            :invalid="!nameValid"
            @focusout="updateNameValid"
        />
      </label>
    </div>

    <div class="w-full md:w-1/2 mb-4" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 font-semibold" for="email">
        {{ t("Email") }}
          <InputText
              id="email"
              v-model="client.email"
              name="email"
              placeholder="john.doe@domain.com"
              :invalid="!emailValid"
              @focusout="updateEmailValid"
          />
      </label>
    </div>

    <div class="w-full md:w-1/2 mb-4" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 font-semibold" for="phone">
        {{ t("Phone") }}
          <InputText
              id="phone"
              v-model="client.phone"
              name="phone"
              placeholder="+1 561-555-7689"
              :invalid="!phoneValid"
              @focusout="updatePhoneValid"
          />
      </label>
    </div>

    <div class="w-full md:w-1/2 mb-8" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 font-semibold" for="photo">
        {{ t("Photo") }}
        <ImageUploadInput
            v-if="!hasAvatar()"
            @file-uploaded="updateAvatar"
        />
        <span v-else class="flex flex-row items-center gap-2">
          <img :src="client.avatar !== null ? client.avatar : ''" alt="avatar" class="w-20 h-20 rounded-full"/>
        <i class="pi pi-times-circle text-red-500 cursor-pointer" @click="clearAvatar"></i>
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
  </div>
</template>
