<template>
  <button
    class="inline-flex text-center items-center w-full md:w-auto px-6 py-3 bg-red-400 hover:bg-red-600 rounded transition duration-200">
    Delete
  </button>
</template>

<script>
export default {
  name: "DeleteButton",
  props: {
    title: String
  }
};
</script>

<style scoped>

</style>
