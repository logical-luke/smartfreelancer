<template>
  <button
    type="button"
    @click="goBack"
    class="inline-flex text-center items-center w-full md:w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
  >
    <arrow-back-up-icon />
    Back
  </button>
</template>

<script>
import ArrowBackUpIcon from "vue-tabler-icons/icons/ArrowBackUpIcon";

export default {
  name: "BackButton",
  components: { ArrowBackUpIcon },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped></style>
