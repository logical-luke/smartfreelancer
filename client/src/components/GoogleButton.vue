<template>
  <button
    class="flex w-full justify-center items-center px-8 py-4 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    type="button"
    @click.prevent="loginWithGoogle"
  >
    <div class="flex items-center w-full">
      <div class="flex items-center justify-start flex-grow-0">
      </div>
      <div class="flex items-center justify-center flex-grow">
        <span><slot></slot> {{ $t("with") }} Google</span>
      </div>
    </div>
  </button>
</template>

<script>
import api from "@/services/api";

export default {
  name: "GoogleButton",
  methods: {
    async loginWithGoogle() {
      window.location.href = await api.postGoogleStart();
    },
  },
};
</script>
