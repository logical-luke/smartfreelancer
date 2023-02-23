<template>
  <div></div>
</template>

<script>
import router from "../../router";
import store from "@/store";
import api from "@/services/api/api";

export default {
  name: "GoogleLoginPage",
  data() {
    return {
      spinnerSize: "96 px",
      spinnerColor: "#382CDD",
    };
  },
  async mounted() {
    store.commit("setInitialLoaded", false);
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
