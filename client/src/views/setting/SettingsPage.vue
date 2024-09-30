<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import PrimaryActionButton from "@/components/form/PrimaryActionButton.vue";
import SecondaryActionButton from "@/components/form/SecondaryActionButton.vue";
import DestructiveActionButton from "@/components/form/DestructiveActionButton.vue";
import Select from 'primevue/select';
import ToggleButton from 'primevue/togglebutton';
import { ref } from 'vue';

const generalSettings = ref({
  language: 'English',
  timezone: 'UTC',
});

const notificationSettings = ref({
  emailNotifications: true,
  smsNotifications: false,
});

const privacySettings = ref({
  profileVisibility: 'Public',
  dataSharing: false,
});

const languages = ['English', 'Spanish', 'French'];
const timezones = ['UTC', 'GMT', 'PST'];
const visibilityOptions = ['Public', 'Private'];

function saveSettings() {
  // Implement save logic here
  console.log('Settings saved');
}

function resetSettings() {
  // Implement reset logic here
  console.log('Settings reset');
}
</script>

<template>
  <PageHeader title="Settings" icon="pi-cog" />

  <div class="container mt-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
        <i class="pi pi-cog mr-2"></i>General Settings
      </h3>
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300">Language</label>
        <Select v-model="generalSettings.language" :options="languages" class="w-full p-2 border rounded" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300">Timezone</label>
        <Select v-model="generalSettings.timezone" :options="timezones" class="w-full p-2 border rounded" />
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
        <i class="pi pi-bell mr-2"></i>Notification Settings
      </h3>
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300">Email Notifications</label>
        <ToggleButton v-model="notificationSettings.emailNotifications" class="mr-2" />
        Enable
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300">SMS Notifications</label>
        <ToggleButton v-model="notificationSettings.smsNotifications" class="mr-2" />
        Enable
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">
        <i class="pi pi-lock mr-2"></i>Privacy Settings
      </h3>
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300">Profile Visibility</label>
        <Select v-model="privacySettings.profileVisibility" :options="visibilityOptions" class="w-full p-2 border rounded" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-300">Data Sharing</label>
        <ToggleButton v-model="privacySettings.dataSharing" class="mr-2" />
        Enable
      </div>
    </div>

    <div class="flex justify-end space-x-2">
      <PrimaryActionButton @click="saveSettings">Save</PrimaryActionButton>
      <SecondaryActionButton @click="resetSettings">Reset</SecondaryActionButton>
    </div>
  </div>
</template>