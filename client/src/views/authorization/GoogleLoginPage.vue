<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthorizationStore } from "@/stores/auth";
import api from "@/services/api";

export default defineComponent({
  name: "GoogleLoginPage",
  setup() {
    const router = useRouter();
    const route = useRoute();
    const authStore = useAuthorizationStore();

    onMounted(async () => {
      const code = route.query.code as string;
      const state = route.query.state as string;

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
        const response = await api.postGoogleCheck({ code, state });
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

        await authStore.authorize(token, refreshToken);
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
    });
  },
});
</script>

<template>
  <div class="flex h-screen items-center justify-center">
  </div>
</template>
