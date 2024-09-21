<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import SidebarNav from "@/components/navigation/SidebarNav.vue";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";
import { useRoute } from "vue-router";
import { useAuthorizationStore } from "@/stores/auth";

const route = useRoute();

const path = computed<string | undefined>(() => route.name as string | undefined);
const isAuthorizedPage = computed<boolean>(() => route.meta && route.meta.requiresAuth === true);

onMounted(async () => {
  const authStore = useAuthorizationStore();
  await authStore.getTokensFromCookies();
});
</script>

<template>
  <ConfirmDialog />
  <Toast />
  <div class="h-screen" :class="{ 'mx-auto lg:ml-80': isAuthorizedPage }">
    <SidebarNav v-if="isAuthorizedPage" />
    <RouterView v-slot="{ Component }">
      <Transition name="fade" mode="out-in">
        <div :key="path" :class="isAuthorizedPage ? 'py-8 px-8' : ''">
          <Component :is="Component"></Component>
        </div>
      </Transition>
    </RouterView>
  </div>
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

.p-toast {
  max-width: calc(100vw - 40px);
}
</style>
