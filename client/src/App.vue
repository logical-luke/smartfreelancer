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
    await store.dispatch("loadInitial");
  }
});
</script>

<template>
  <transition name="fade" mode="out-in">
    <div
      v-if="!store.getters.isInitialLoaded"
      class="flex justify-center pt-32 md:pt-96 items-center"
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
      <div>
        <SidebarNav v-if="!isLogin" />
      </div>
      <div class="min-h-screen">
        <transition name="fade" mode="out-in">
          <div
            v-if="!isLogin"
            class="sticky top-0 z-50 mx-auto w-full lg:ml-80"
          >
            <HeaderNavbar />
          </div>
        </transition>
        <div v-if="store.getters.isInitialLoaded">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <div class="" :key="this.$route.name">
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
export default {
  name: "App",
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD",
    };
  },
  computed: {
    isLogin() {
      return this.$route.name === "LoginPage";
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
