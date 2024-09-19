<template>
  <button
    type="button"
    class="inline-flex text-center items-center w-full md:w-auto px-8 py-4 text-sm font-medium text-indigo-500 bg-white hover:text-white border-solid border-2 border-indigo-500 hover:bg-indigo-500 rounded transition duration-200"
    @click="goBack"
  >
    Back
  </button>
</template>

<script>
import router from "@/router";

export default {
  name: "BackButton",
  methods: {
    goBack() {
      router.go(-1);
    },
  },
};
</script>

<style scoped></style>
