<template>
  <button
    class="flex justify-center items-center w-full md:w-auto px-6 py-3 border-indigo-500 border text-sm font-medium hover:bg-gray-200 rounded transition duration-200"
    type="button"
    @click.prevent="loginWithGoogle"
  >
    <brand-google-icon />
    <span class="ml-2"
      ><slot></slot> with <span class="text-red-700">Google</span></span
    >
  </button>
</template>

<script>
import BrandGoogleIcon from "vue-tabler-icons/icons/BrandGoogleIcon";
import api from "@/services/api/api";
import store from "@/store";

export default {
  name: "GoogleButton",
  components: { BrandGoogleIcon },
  methods: {
    async loginWithGoogle() {
      store.commit("setInitialLoaded", false);
      window.location.href = await api.postGoogleStart();
    },
  },
};
</script>
