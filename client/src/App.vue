<template>
  <transition name="fade" mode="out-in">
    <div
      class="flex h-screen items-center justify-center"
      v-if="!isInitialLoaded"
    >

      <div class="flex flex-col items-center justify-center">
        <div><moon-loader :size="spinnerSize" :color="spinnerColor" :loading="true" />
        </div>
        <div class="flex items-center justify-center mt-4 p-6">
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
        <transition name="fade" mode="out-in">
          <div v-if="isAuthorizedPage" class="fixed z-20 sticky top-0 w-full">
            <header-navbar />
          </div>
        </transition>
        <div v-if="isInitialLoaded">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <div
                :class="isAuthorizedPage ? 'py-4 md:py-8 px-6' : ''"
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
import SidebarNav from "@/components/ui/SidebarNav.vue";
import HeaderNavbar from "@/components/ui/HeaderNavbar.vue";
import { MoonLoader } from "vue3-spinner";
import ConfirmDialog from "primevue/confirmdialog";
import Toast from "primevue/toast";
import { useRoute } from "vue-router";
import getUTCTimestampFromLocaltime from "@/services/time/getUTCTimestampFromLocaltime";
import RandomLoadingText from "./components/ui/RandomLoadingText.vue";

export default {
  name: "App",
  components: {
    RandomLoadingText,
    SidebarNav,
    HeaderNavbar,
    MoonLoader,
    ConfirmDialog,
    Toast
  },
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD"
    };
  },
  watch: {
    queue() {
      if (this.isQueueEmpty) {
        synchronization.disableBackgroundUpload();
        synchronization.enableBackgroundSync();
      } else {
        synchronization.disableBackgroundSync();
        synchronization.enableBackgroundUpload();
      }
    }
  },
  computed: {
    path() {
      const route = useRoute();

      return route.name;
    },
    isAuthorizedPage() {
      const { meta } = useRoute();

      return meta.requiresAuth === true;
    },
    ...mapGetters("synchronization", ["isInitialLoaded", "isQueueEmpty"]),
    ...mapState({
      queue: (state) => state.synchronization.queue
    })
  },
  setup() {
    onMounted(async () => {
      // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
      let vh = window.innerHeight * 0.01;
      // Then we set the value in the --vh custom property to the root of the document
      document.documentElement.style.setProperty("--vh", `${vh}px`);
      // We listen to the resize event
      window.addEventListener("resize", () => {
        // We execute the same script as before
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty("--vh", `${vh}px`);
      });
      let token = await cookies.get("api_token");
      if (token === "null" || token === "") {
        token = null;
      }
      if (!token) {
        store.commit("synchronization/setInitialLoaded", true);
      }

      let refreshToken = await cookies.get("refresh_token");
      if (refreshToken === "null" || refreshToken === "") {
        refreshToken = null;
      }

      if (token && refreshToken) {
        await authorization.authorize(token, refreshToken);
        await synchronization.syncUser();
        await cache.getInitial();
      }

      setInterval(() => {
        store.commit("time/setServerTime", getUTCTimestampFromLocaltime() + store.getters["time/getServerTimeOffset"]);
      }, 1000);
    });
  }
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-slower-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-slower-enter-from,
.fade-leave-to {
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
  height: 100vh; /* Fallback for browsers that do not support Custom Properties */
  height: calc(var(--vh, 1vh) * 100);
}
</style>
