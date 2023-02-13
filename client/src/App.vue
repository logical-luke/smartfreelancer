<script setup>
import { onMounted } from "vue";
import store from "@/store";

import VueCookies from "vue-cookies";

import SidebarNav from "@/components/ui/SidebarNav.vue";
import HeaderNavbar from "@/components/ui/HeaderNavbar.vue";
import { MoonLoader } from "vue3-spinner";

onMounted(async () => {
  let token = VueCookies.get("api_token");
  if (token === "null" || token === "") {
    token = null;
  }
  if (!token) {
    store.commit("setInitialLoaded", true);
  }
  let refreshToken = VueCookies.get("refresh_token");
  if (refreshToken === "null" || refreshToken === "") {
    refreshToken = null;
  }
  if (token && refreshToken) {
    store.commit("setToken", token);
    store.commit("setAuthorized", true);
    store.commit("setRefreshToken", refreshToken);
    setInterval(() => {
      if (store.getters.getServerTime) {
        store.commit("setServerTime", Number(store.getters.getServerTime) + 1);
      }
    }, 1000);
    await store.dispatch("loadInitial");
    await store.dispatch("sync");
  }
});
</script>

<template>
  <transition name="fade" mode="out-in">
    <div
      class="grid h-screen place-items-center"
      v-if="!store.getters.isInitialLoaded"
    >
      <div>
        <MoonLoader
          :size="spinnerSize"
          :color="spinnerColor"
          :loading="!store.getters.isInitialLoaded"
        />
      </div>
    </div>
    <div v-else>
      <div
        class="min-h-screen"
        :class="{ 'mx-auto lg:ml-80': isAuthorizedPage }"
      >
        <SidebarNav v-if="isAuthorizedPage" />
        <transition name="fade" mode="out-in">
          <div
            v-if="isAuthorizedPage"
            class="fixed bottom-0 z-20 lg:sticky lg:top-0 w-full"
          >
            <HeaderNavbar />
          </div>
        </transition>
        <div v-if="store.getters.isInitialLoaded">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <div class="py-8 px-6" :key="path">
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
import { useRoute } from "vue-router";

export default {
  name: "App",
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD",
    };
  },
  setup() {},
  computed: {
    path() {
      const route = useRoute();

      return route.name;
    },
    isAuthorizedPage() {
      const { meta } = useRoute();

      return meta.requiresAuth === true;
    },
  },
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
</style>
