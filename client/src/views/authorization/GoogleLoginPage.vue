<template>
  <div class="flex h-screen items-center justify-center">
    <moon-loader :size="spinnerSize" :color="spinnerColor" :loading="true" />
  </div>
</template>

<script>
import router from "../../router";
import store from "@/store";
import api from "@/services/api";
import authorization from "@/services/authorization";
import synchronization from "@/services/synchronization";
import { MoonLoader } from "vue3-spinner";

export default {
  name: "GoogleLoginPage",
  components: { MoonLoader },
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD",
    };
  },
  async mounted() {
    const code = this.$route.query.code;
    const state = this.$route.query.state;

    if (!code || !state) {
      this.$toast.add({
        severity: "error",
        summary: this.$i18n.t("Unable to sign in"),
        detail: this.$i18n.t("Please try again"),
        life: 5000,
      });
      await router.push("/login");

      return;
    }
    try {
      const response = await api.postGoogleCheck({ code: code, state: state });
      const token = response.token;
      const refreshToken = response.refreshToken;
      if (!token || !refreshToken) {
        this.$toast.add({
          severity: "error",
          summary: this.$i18n.t("Unable to sign in"),
          detail: this.$i18n.t("Please try again"),
          life: 5000,
        });
        await router.push("/login");

        return;
      }
      await authorization.authorize(token, refreshToken);
      await store.commit("synchronization/setInitialLoaded", false);
      await synchronization.syncUser();
      await synchronization.syncAll();
      await store.commit("synchronization/setInitialLoaded", true);

      await router.push("/");
    } catch (e) {
      this.$toast.add({
        severity: "error",
        summary: this.$i18n.t("Unable to sign in"),
        detail: this.$i18n.t("Please try again"),
        life: 5000,
      });
      await router.push("/login");
    }
  },
  beforeRouteEnter() {
    store.commit("synchronization/setInitialLoaded", true);
  },
};
</script>

<style scoped></style>
