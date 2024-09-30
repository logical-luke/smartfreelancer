<script setup lang="ts">
import PageHeader from "@/components/page/PageHeader.vue";
import SectionWrapper from "@/components/SectionWrapper.vue";
import Select from 'primevue/select';
import ToggleButton from 'primevue/togglebutton';
import InputText from 'primevue/inputtext';
import { ref } from 'vue';
import ActionButton from "@/components/form/ActionButton.vue";

const generalSettings = ref({
  language: 'English',
  timezone: 'UTC',
  dateFormat: 'MM/DD/YYYY',
  theme: 'Light',
});

const notificationSettings = ref({
  emailNotifications: true,
  smsNotifications: false,
  pushNotifications: true,
  notificationFrequency: 'Daily',
});

const privacySettings = ref({
  profileVisibility: 'Public',
  dataSharing: false,
  twoFactorAuth: true,
});

const languages = ['English', 'Spanish', 'French', 'German', 'Chinese'];
const timezones = ['UTC', 'GMT', 'PST', 'EST', 'CST'];
const dateFormats = ['MM/DD/YYYY', 'DD/MM/YYYY', 'YYYY-MM-DD'];
const themes = ['Light', 'Dark', 'System'];
const visibilityOptions = ['Public', 'Private', 'Friends Only'];
const notificationFrequencies = ['Immediately', 'Daily', 'Weekly', 'Monthly'];

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
  <PageHeader title="Settings" icon="pi pi-cog" />

  <div class="container mt-4">
    <SectionWrapper title="General Settings" icon="pi pi-cog" color="blue">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 dark:text-gray-300">Language</label>
          <Select v-model="generalSettings.language" :options="languages" class="w-full" />
        </div>
        <div>
          <label class="block text-gray-700 dark:text-gray-300">Timezone</label>
          <Select v-model="generalSettings.timezone" :options="timezones" class="w-full" />
        </div>
        <div>
          <label class="block text-gray-700 dark:text-gray-300">Date Format</label>
          <Select v-model="generalSettings.dateFormat" :options="dateFormats" class="w-full" />
        </div>
        <div>
          <label class="block text-gray-700 dark:text-gray-300">Theme</label>
          <Select v-model="generalSettings.theme" :options="themes" class="w-full" />
        </div>
      </div>
    </SectionWrapper>

    <SectionWrapper title="Notification Settings" icon="pi pi-bell" color="green">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center">
          <ToggleButton v-model="notificationSettings.emailNotifications" class="mr-2" />
          <label>Email Notifications</label>
        </div>
        <div class="flex items-center">
          <ToggleButton v-model="notificationSettings.smsNotifications" class="mr-2" />
          <label>SMS Notifications</label>
        </div>
        <div class="flex items-center">
          <ToggleButton v-model="notificationSettings.pushNotifications" class="mr-2" />
          <label>Push Notifications</label>
        </div>
        <div>
          <label class="block text-gray-700 dark:text-gray-300">Notification Frequency</label>
          <Select v-model="notificationSettings.notificationFrequency" :options="notificationFrequencies" class="w-full" />
        </div>
      </div>
    </SectionWrapper>

    <SectionWrapper title="Privacy Settings" icon="pi pi-lock" color="purple">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 dark:text-gray-300">Profile Visibility</label>
          <Select v-model="privacySettings.profileVisibility" :options="visibilityOptions" class="w-full" />
        </div>
        <div class="flex items-center">
          <ToggleButton v-model="privacySettings.dataSharing" class="mr-2" />
          <label>Data Sharing</label>
        </div>
        <div class="flex items-center">
          <ToggleButton v-model="privacySettings.twoFactorAuth" class="mr-2" />
          <label>Two-Factor Authentication</label>
        </div>
      </div>
    </SectionWrapper>

    <div class="flex justify-end space-x-2 mt-6">
      <ActionButton type="secondary" @click="resetSettings">Reset to Default</ActionButton>
      <ActionButton @click="saveSettings">Save Settings</ActionButton>
    </div>
  </div>
</template>