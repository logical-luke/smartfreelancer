<script setup lang="ts">
import {useI18n} from "vue-i18n";
const { t } = useI18n();
import { ref, computed, watch } from 'vue';
import { useAuthorizationStore } from '@/stores/auth';
import SidebarItem from '@/components/navigation/SidebarItem.vue';
import SidebarLogout from '@/components/navigation/SidebarLogout.vue';
import LanguageSwitcher from '@/components/navigation/LanguageSwitcher.vue';
import IconOnlyLogo from '@/components/logo/IconOnlyLogo.vue';
import {useRoute} from "vue-router";

const authStore = useAuthorizationStore();
const route = useRoute();

const isAuthorized = computed(() => authStore.isAuthorized);
const open = ref(false);

const toggle = () => {
  open.value = !open.value;
};

watch(() => route, () => {
  open.value = false;
});
</script>

<template>
  <transition name="fade" mode="in-out">
    <div v-if="isAuthorized" class="relative z-50">
      <nav class="lg:hidden py-6 px-4 bg-gray-800">
        <div class="flex flex-row items-center justify-between mr-2">
          <div class="flex">
            <IconOnlyLogo />
          </div>
          <div class="flex-row flex items-center justify-end gap-2">
            <button
              class="navbar-burger p-2 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
              @click="toggle"
            >
              <i class="pi pi-bars"></i>
            </button>
          </div>
        </div>
      </nav>
      <div :class="{ hidden: !open }" class="lg:block navbar-menu relative z-50">
        <div class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-10"></div>
        <nav class="flex fixed top-0 left-0 bottom-0 flex-col w-full lg:max-w-xs lg:w-3/4 pt-6 pb-8 bg-gray-800 overflow-y-auto">
          <div class="flex flex-row justify-between items-center px-4 pb-8 mr-2 lg:mr-0 mb-8">
            <div class="flex">
              <IconOnlyLogo />
            </div>
            <div class="flex flex-row items-center justify-end gap-2">
              <button
                class="lg:hidden navbar-burger p-2 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
                @click="toggle"
              >
                <i class="pi pi-bars"></i>
              </button>
            </div>
          </div>
          <div class="px-4 pb-8">
            <ul class="mb-8 text-sm font-medium">
              <SidebarItem go-to="/">
                {{ t("Deep Work Hub") }}
                <template #icon>
                  <i class="pi pi-bullseye"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/calendar">
                {{ t("Calendar") }}
                <template #icon>
                  <i class="pi pi-calendar"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/tasks">
                {{ t("Tasks") }}
                <template #icon>
                  <i class="pi pi-check-circle"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/projects">
                {{ t("Projects") }}
                <template #icon>
                  <i class="pi pi-folder"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/clients">
                {{ t("Clients") }}
                <template #icon>
                  <i class="pi pi-user"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/reports">
                {{ t("Reports") }}
                <template #icon>
                  <i class="pi pi-chart-bar"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/invoices">
                {{ t("Invoices") }}
                <template #icon>
                  <i class="pi pi-receipt"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/expenses">
                {{ t("Expenses") }}
                <template #icon>
                  <i class="pi pi-money-bill"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/automations">
                {{ t("Automations") }}
                <template #icon>
                  <i class="pi pi-bolt"></i>
                </template>
              </SidebarItem>
              <SidebarItem go-to="/settings">
                {{ t("Settings") }}
                <template #icon>
                  <i class="pi pi-cog"></i>
                </template>
              </SidebarItem>
              <SidebarLogout />
            </ul>
            <LanguageSwitcher />
          </div>
        </nav>
      </div>
    </div>
  </transition>
</template>
