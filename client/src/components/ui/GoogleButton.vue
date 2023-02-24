<template>
  <button
    class="flex justify-center items-center w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    type="button"
    @click.prevent="loginWithGoogle"
  >
    <brand-google-icon size="18" />
    <span class="ml-2">
      <slot></slot> {{ $t("with") }} Google
    </span>
  </button>
</template>

<script>
import BrandGoogleIcon from "vue-tabler-icons/icons/BrandGoogleIcon";
import api from "@/services/api";
import store from "@/store";

export default {
  name: "GoogleButton",
  components: { BrandGoogleIcon },
  methods: {
    async loginWithGoogle() {
      store.commit("synchronization/setInitialLoaded", false);
      window.location.href = await api.postGoogleStart();
    }
  }
};
</script>
