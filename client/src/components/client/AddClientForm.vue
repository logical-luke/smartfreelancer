<script setup lang="ts">
import {useI18n} from "vue-i18n";
const { t } = useI18n();
import { ref } from 'vue';
import { useClientsStore } from '@/stores/clients';
import InputText from 'primevue/inputtext';
import MainActionButton from '@/components/form/PrimaryActionButton.vue';
import ActionButton from '@/components/form/SecondaryActionButton.vue';
import ImageUploadInput from '@/components/form/ImageUploadInput.vue';
import { useRouter } from 'vue-router';
import Avatar from 'primevue/avatar';
import OverlayBadge from 'primevue/overlaybadge';
import AvatarGroup from 'primevue/avatargroup';

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
  <div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2" for="name">
            {{ t("Name") }} <span class="text-red-500">*</span>
          </label>
          <InputText
              id="name"
              v-model="client.name"
              name="name"
              placeholder="John Doe"
              :class="{'border-red-500': !nameValid}"
              class="w-full p-3 rounded-md border focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
              @focusout="updateNameValid"
              @keyup.enter="submitForm"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2" for="email">
            {{ t("Email") }}
          </label>
          <InputText
              id="email"
              v-model="client.email"
              name="email"
              placeholder="john.doe@domain.com"
              :class="{'border-red-500': !emailValid}"
              class="w-full p-3 rounded-md border focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
              @focusout="updateEmailValid"
              @keyup.enter="submitForm"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2" for="phone">
            {{ t("Phone") }}
          </label>
          <InputText
              id="phone"
              v-model="client.phone"
              name="phone"
              placeholder="+1 561-555-7689"
              :class="{'border-red-500': !phoneValid}"
              class="w-full p-3 rounded-md border focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
              @focusout="updatePhoneValid"
              @keyup.enter="submitForm"
          />
        </div>
      </div>

      <div class="col-span-1 md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2" for="photo">
          {{ t("Photo") }}
        </label>
        <div class="flex items-center">
          <div v-if="hasAvatar()" class="mr-4 relative">
            <AvatarGroup>
              <OverlayBadge @click="clearAvatar" value="X" severity="danger" class="inline-flex">
                <Avatar
                    class="p-overlay-badge"
                    :image="client.avatar"
                    size="xlarge"
                    shape="circle"
                    :alt="client.name" />
              </OverlayBadge>
            </AvatarGroup>
          </div>
          <ImageUploadInput
              v-if="!hasAvatar()"
              @file-uploaded="updateAvatar"
              class="flex-grow"
          />
        </div>
      </div>

      <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
        <RouterLink :to="cancelPageRoute">
          <ActionButton>{{ t("Cancel") }}</ActionButton>
        </RouterLink>
        <MainActionButton
            :disabled="!canSubmitForm() || client.name.length === 0"
            @click="submitForm"
        >
          {{ t("Add Client") }}
        </MainActionButton>
      </div>
    </div>
  </div>
</template>
