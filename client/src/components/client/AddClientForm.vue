<script setup lang="ts">
import {useI18n} from "vue-i18n";
const { t } = useI18n();
import { ref } from 'vue';
import { useClientsStore } from '@/stores/clients';
import InputText from 'primevue/inputtext';
import MainActionButton from '@/components/form/MainActionButton.vue';
import ActionButton from '@/components/form/ActionButton.vue';
import ImageUploadInput from '@/components/form/ImageUploadInput.vue';
import { useRouter } from 'vue-router';

const clientsStore = useClientsStore();
const router = useRouter();
const client = ref({
  name: '',
  email: '',
  phone: '',
  avatar: '',
});
const nameValid = ref(true);
const emailValid = ref(true);
const phoneValid = ref(true);
const cancelPageRoute = ref({ name: "ClientsPage" });
const afterSaveRoute = ref({ name: "ClientsPage" });

const updateNameValid = () => {
  nameValid.value = client.value.name.length > 0;
};

const updateEmailValid = () => {
  emailValid.value = /\S+@\S+\.\S+/.test(client.value.email);
};

const updatePhoneValid = () => {
  phoneValid.value = /^\+?[1-9]\d{1,14}$/.test(client.value.phone);
};

const updateAvatar = (avatar: string) => {
  client.value.avatar = avatar;
};

const clearAvatar = () => {
  client.value.avatar = '';
};

const hasAvatar = () => {
  return client.value.avatar.length > 0;
};

const canSubmitForm = () => {
  return nameValid.value && emailValid.value && phoneValid.value;
};

const submitForm = async () => {
  if (canSubmitForm()) {
    await clientsStore.create(client.value);
    router.push(afterSaveRoute.value);
  }
};
</script>

<template>
  <div>
    <div class="w-full md:w-1/2 mb-4" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 font-semibold" for="name">
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
          <img :src="client.avatar" alt="avatar" class="w-20 h-20 rounded-full" />
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
        {{ t("Add") }}
      </MainActionButton>
      <router-link :to="cancelPageRoute">
        <ActionButton class="w-full md:w-auto">{{ t("Cancel") }}</ActionButton>
      </router-link>
    </div>
  </div>
</template>
