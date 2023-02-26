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
  async beforeRouteEnter(to) {
    const code = to.query.code;
    const state = to.query.state;

    if (!code || !state) {
      await router.push("/login");

      return;
    }
    try {
      const response = await api.postGoogleCheck({ code: code, state: state });
      const token = response.token;
      const refreshToken = response.refreshToken;
      if (!token || !refreshToken) {
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
