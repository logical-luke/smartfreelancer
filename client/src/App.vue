<script setup>
import Sidebar from "@/components/Sidebar.vue";
import { onMounted } from "vue";
import VueCookies from "vue-cookies";
import store from "@/store";
import api from "@/api/api";

onMounted(async () => {
  let token = VueCookies.get("api_token");
  if (token === "null" || token === '') {
    token = null;
  }
  store.commit('setToken', token);
  let refreshToken = VueCookies.get("refresh_token");
  if (refreshToken === "null" || refreshToken === '') {
    refreshToken = null;
  }
  store.commit('setRefreshToken', refreshToken);
  store.commit('setAuthorized', true);
  const user = await api.getUser();
  store.commit('setUser', user);
});
</script>

<template>
  <div v-if="this.$route.name !== 'Login'" class="mx-auto lg:ml-80">
    <Sidebar />
    <router-view />
  </div>
  <div v-else>
    <router-view />
  </div>
</template>

