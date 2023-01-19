<template>
  <div class="container pt-8 px-4">
    <router-link :to="goTo">
      <a class="flex mr-4 items-center">
        <button
          type="button"
          class="inline-flex text-center items-center w-full md:w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200">
          New
          <slot></slot>
        </button>
      </a>
    </router-link>
  </div>
</template>

<script>
export default {
  name: "NewButton",
  props: {
    goTo: {
      type: String,
      required: true
    }
  }
};
</script>

<style scoped>

</style>
