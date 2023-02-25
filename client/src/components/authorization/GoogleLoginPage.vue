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
    store.commit("synchronization/setInitialLoaded", false);
    const code = this.$route.query.code;
    const state = this.$route.query.state;

    if (!code || !state) {
      await router.push("/login");
    }

    const { token, refreshToken } = await api.postGoogleCheck({ code: code, state: state });
    await authorization.authorize(token, refreshToken);
    await store.commit("synchronization/setInitialLoaded", false);
    await time.enableServerTimeSync();
    await synchronization.syncAll();
    await store.commit("synchronization/setInitialLoaded", true);

    await router.push("/");
  }
};
</script>

<style scoped></style>
