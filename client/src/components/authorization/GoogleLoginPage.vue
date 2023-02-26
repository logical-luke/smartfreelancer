<template>
  <div></div>
</template>

<script>
import router from "../../router";
import store from "@/store";
import api from "@/services/api";
import authorization from "@/services/authorization";
import time from "@/services/synchronization/time";
import synchronization from "@/services/synchronization";

export default {
  name: "GoogleLoginPage",
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD"
    };
  },
  async mounted() {
    const code = this.$route.query.code;
    const state = this.$route.query.state;

    if (!code || !state) {
      console.log("No code or state found");
      await router.push("/login");

      return;
    }
    try {
      const response = await api.postGoogleCheck({ code: code, state: state });
      const token = response.token;
      const refreshToken = response.refreshToken;
      if (!token || !refreshToken) {
        console.log("No token or refresh token found");
        await router.push("/login");

        return;
      }
      await authorization.authorize(token, refreshToken);
      await store.commit("synchronization/setInitialLoaded", false);
      await time.enableServerTimeSync();
      await synchronization.syncAll();
      await store.commit("synchronization/setInitialLoaded", true);

      await router.push("/");
    } catch (e) {
      await router.push("/login");
    }
  }
};
</script>

<style scoped></style>
