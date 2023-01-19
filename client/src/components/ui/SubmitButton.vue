<template>
  <button
    class="inline-block w-full md:w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    type="submit">{{ title }}
  </button>
</template>

<script>
export default {
  name: "SubmitButton",
  props: {
    title: String
  }
};
</script>

<style scoped>

</style>
