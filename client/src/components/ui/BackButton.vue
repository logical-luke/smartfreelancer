<template>
  <button
    type="button"
    @click="goBack"
    class="inline-flex text-center items-center w-full md:w-auto px-6 py-3 font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200">
    Back
  </button>
</template>

<script>
export default {
  name: "BackButton",
  methods: {
    goBack() {
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>

</style>
