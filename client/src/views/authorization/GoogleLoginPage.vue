<template>
  <div class="flex h-screen items-center justify-center">
  </div>
</template>

<script>
import router from "../../router";
import api from "@/services/api";
import authorization from "@/services/authorization";

export default {
  name: "GoogleLoginPage",
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
};
</script>

<style scoped></style>
