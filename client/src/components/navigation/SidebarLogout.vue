<script setup lang="ts">
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
import { useRouter } from 'vue-router';
import { useAuthorizationStore } from '@/stores/auth';

const authStore = useAuthorizationStore();
const router = useRouter();

const logout = async () => {
  await authStore.logout();
  await router.push('/login');
};
</script>

<template>
  <a
    class="flex cursor-pointer items-center pl-3 py-4 pr-2 text-gray-50 hover:bg-gray-900 rounded"
    @click="logout"
  >
    <span class="inline-block mr-3">
      <i class="pi pi-power-off"></i>
    </span>
    <span>{{ t("Log out") }}</span>
  </a>
</template>
