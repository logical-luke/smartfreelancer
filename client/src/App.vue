<script>
import { onMounted } from "vue";
import authorization from "@/services/authorization";
import cache from "@/services/cache";
import SidebarNav from "@/components/navigation/SidebarNav.vue";
import TimeTrackingSection from "@/components/timer/TimeTrackingSection.vue";
import { RotateLoader } from "vue3-spinner";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";
import { useRoute } from "vue-router";
import getUTCTimestampFromLocaltime from "@/services/time/getUTCTimestampFromLocaltime";
import RandomLoadingText from "./components/RandomLoadingText.vue";

export default {
  name: "App",
  components: {
    RandomLoadingText,
    SidebarNav,
    TimeTrackingSection,
    RotateLoader,
    ConfirmDialog,
    Toast,
  },
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD",
      isInitialLoaded: true,
    };
  },
  computed: {
    path() {
      return this.route.name;
    },
    isAuthorizedPage() {
      return this.route.meta && this.route.meta.requiresAuth === true;
    },
  },
  setup() {
    const route = useRoute();
    onMounted(async () => {
      const { token, refreshToken } = await authorization.getTokensFromCookies();
      await authorization.authorize(token, refreshToken);

      await cache.loadLocale();
    });

    return { route };
  },
};
</script>

<template>
  <transition name="initial">
    <div
      class="flex h-screen items-center justify-center"
      v-if="!isInitialLoaded"
    >
      <div class="flex flex-col items-center justify-center">
        <div>
          <rotate-loader
            :color="spinnerColor"
            :loading="true"
          />
        </div>
        <div class="flex items-center justify-center mt-4 p-4">
          <span class="text-center"><random-loading-text /></span>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="h-screen" :class="{ 'mx-auto lg:ml-80': isAuthorizedPage }">
        <toast
          :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
          :closeButtonProps="{ style: { 'box-shadow': 'none' } }"
          errorIcon="pi pi-minus-circle"
        />
        <confirm-dialog />
        <sidebar-nav v-if="isAuthorizedPage" />
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <div
              :class="isAuthorizedPage ? 'py-8 px-8' : ''"
              :key="path"
            >
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
