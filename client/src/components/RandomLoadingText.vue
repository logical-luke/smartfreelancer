<script setup lang="ts">
import { onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthorizationStore } from "@/stores/auth";
import api from "@/services/api";
import { useToast } from "primevue/usetoast";
import i18n from "@/services/locale/i18n";

const router = useRouter();
const route = useRoute();
const authStore = useAuthorizationStore();
const toast = useToast();

onMounted(async () => {
  const code = route.query.code as string;
  const state = route.query.state as string;

  if (!code || !state) {
    toast.add({
      severity: "error",
      summary: i18n.global.t("Unable to sign in"),
      detail: i18n.global.t("Please try again"),
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
      toast.add({
        severity: "error",
        summary: i18n.global.t("Unable to sign in"),
        detail: i18n.global.t("Please try again"),
        life: 5000,
      });
      await router.push("/login");
      return;
    }

    await authStore.authorize(token, refreshToken);
    await router.push("/");
  } catch (e) {
    toast.add({
      severity: "error",
      summary: i18n.global.t("Unable to sign in"),
      detail: i18n.global.t("Please try again"),
      life: 5000,
    });
    await router.push("/login");
  }
});
</script>

<template>
  <div class="flex h-screen items-center justify-center">
  </div>
</template>
