<template>
  <transition name="fade">
    <div
      class="flex h-screen items-center justify-center"
      v-if="!isInitialLoaded"
    >
      <div class="flex flex-col items-center justify-center">
        <div>
          <moon-loader
            :size="spinnerSize"
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
        <div v-if="isInitialLoaded">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <div
                :class="isAuthorizedPage ? 'py-4 md:py-8 px-8' : ''"
                :key="path"
              >
                <component :is="Component"></component>
              </div>
            </transition>
          </router-view>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import { onMounted } from "vue";
import { mapGetters, mapState } from "vuex";
import store from "@/store";
import cookies from "@/services/cookies";
import authorization from "@/services/authorization";
import cache from "@/services/cache";
import synchronization from "@/services/synchronization";
import SidebarNav from "@/components/navigation/SidebarNav.vue";
import TimeTrackingSection from "@/components/timer/TimeTrackingSection.vue";
import { MoonLoader } from "vue3-spinner";
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
    MoonLoader,
    ConfirmDialog,
    Toast,
  },
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD",
    };
  },
  watch: {
    queue() {
      if (this.isQueueEmpty) {
        synchronization.disableBackgroundUpload();
        synchronization.enableBackgroundFetching();

        return;
      }

      synchronization.disableBackgroundFetching();
      synchronization.enableBackgroundUpload();
    },
  },
  computed: {
    path() {
      return this.route.name;
    },
    isAuthorizedPage() {
      return this.route.meta && this.route.meta.requiresAuth === true;
    },
    ...mapGetters("synchronization", ["isInitialLoaded", "isQueueEmpty"]),
    ...mapState({
      queue: (state) => state.synchronization.queue,
    }),
  },
  setup() {
    const route = useRoute();
    onMounted(async () => {
      let vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty("--vh", `${vh}px`);
      window.addEventListener("resize", () => {
        // We execute the same script as before
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty("--vh", `${vh}px`);
      });

      const { token, refreshToken } = await authorization.getTokensFromCookies();
      await authorization.authorize(token, refreshToken);

      await cache.loadLocale();

      if (token) {
        await synchronization.fetchUser();
        await cache.getInitial();
        await synchronization.disableBackgroundUpload();
        await synchronization.enableBackgroundFetching();
      }
    });

    return { route };
  },
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.4s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
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

button.p-button.p-component.p-confirm-dialog-accept.confirm-button-accept {
  background-color: #ef4444 !important;
  border: none !important;
}

button.p-button.p-component.p-confirm-dialog-accept.confirm-button-accept:enabled:hover {
  background-color: #dc2626 !important;
  border: none !important;
}

::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-thumb {
  background-color: #6a707e;
  border-radius: 4px;
}

.h-screen {
  /* Fallback for browsers that do not support Custom Properties */
  height: 100vh;
}

.h-screen {
  height: calc(1vh * 100);
}


</style>
