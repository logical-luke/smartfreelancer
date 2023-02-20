<template>
  <div class="grid h-screen place-items-center">
    <div>
      <moon-loader :size="spinnerSize" :color="spinnerColor" :loading="true" />
    </div>
  </div>
</template>

<script>
import router from "../router";
import api from "@/services/api/api";
import store from "@/store";

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
      await router.push("/login");
    }

    const tokens = await api.postGoogleCheck({ code: code, state: state });
    await store.dispatch("login", tokens);
  },
};
</script>

<style scoped></style>
