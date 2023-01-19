<script setup>
import Sidebar from "@/components/ui/Sidebar.vue";
import { onMounted } from "vue";
import VueCookies from "vue-cookies";
import store from "@/store";
import api from "@/api/api";


onMounted(async () => {
  let token = VueCookies.get("api_token");
  if (token === "null" || token === '') {
    token = null;
  }
  let refreshToken = VueCookies.get("refresh_token");
  if (refreshToken === "null" || refreshToken === '') {
    refreshToken = null;
  }
  if (token && refreshToken) {
    store.commit('setToken', token);
    store.commit('setAuthorized', true);
    store.commit('setRefreshToken', refreshToken);
    const user = await api.getUser();
    store.commit('setUser', user);
  }
});
</script>

<template>
  <transition name="fade" mode="out-in">
    <div>
      <Sidebar v-if="!isLogin" />
    </div>
  </transition>
  <router-view v-slot="{ Component }">
    <transition name="fade" mode="out-in">
      <div :key="this.$route.name">
        <component :is="Component"></component>
      </div>
    </transition>
  </router-view>
</template>

<script>
  export default {
    name: "App",
    computed: {
      isLogin() {
        return this.$route.name === 'Login'
      },
    }
  };

</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
