<template>
  <button
    class="flex w-full justify-center items-center w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    type="button"
    @click.prevent="loginWithGoogle"
  >
    <div class="flex items-center w-full">
      <div class="flex items-center justify-start flex-grow-0">
        <brand-google-icon size="18" />
      </div>
      <div class="flex items-center justify-center flex-grow">
        <span><slot></slot> {{ $t("with") }} Google</span>
      </div>
    </div>
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
      window.location.href = await api.postGoogleStart();
    },
  },
};
</script>
