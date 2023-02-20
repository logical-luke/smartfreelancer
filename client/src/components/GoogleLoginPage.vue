<template>

</template>

<script>
import router from "../router";
import api from "@/services/api/api";
import store from "@/store";

export default {
  name: "GoogleLoginPage",
  async mounted() {
    const code = this.$route.query.code;
    const state = this.$route.query.state;

    if (!code || !state) {
      await router.push('/login')
    }

    const tokens = await api.postGoogleCheck({code: code, state: state});

    await store.dispatch('login', tokens);
  },
};
</script>

<style scoped>

</style>
