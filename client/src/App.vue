<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import SidebarNav from "@/components/navigation/SidebarNav.vue";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";
import { useRoute } from "vue-router";
import RandomLoadingText from "./components/RandomLoadingText.vue";
import { useAuthorizationStore } from "@/stores/auth";

const route = useRoute();

const isInitialLoaded = ref<boolean>(true);

const path = computed<string | undefined>(() => route.name as string | undefined);
const isAuthorizedPage = computed<boolean>(() => route.meta && route.meta.requiresAuth === true);

onMounted(async () => {
  const authStore = useAuthorizationStore();
  await authStore.getTokensFromCookies();
});
</script>

<template>
  <transition name="initial">
    <div v-if="!isInitialLoaded" class="flex h-screen items-center justify-center">
      <div class="flex flex-col items-center justify-center">
        <div></div>
        <div class="flex items-center justify-center mt-4 p-4">
          <span class="text-center"><random-loading-text /></span>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="h-screen" :class="{ 'mx-auto lg:ml-80': isAuthorizedPage }">
        <toast
            :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
            :close-button-props="{ style: { 'box-shadow': 'none' } }"
            error-icon="pi pi-minus-circle"
        />
        <confirm-dialog />
        <sidebar-nav v-if="isAuthorizedPage" />
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <div :key="path" :class="isAuthorizedPage ? 'py-8 px-8' : ''">
              <component :is="Component"></component>
            </div>
          </transition>
        </router-view>
      </div>
    </div>
  </transition>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.4s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.initial-enter-active {
  transition: all 0.2s ease-in;
}

.initial-leave-active {
  transition: all 0.9s ease-out;
}

.initial-enter-from,
.initial-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease-out;
}

.slide-enter-from {
  transform: translateX(-100%);
  opacity: 0;
}

.slide-leave-to {
  transform: translateX(100%);
  opacity: 0;
}
</style>
