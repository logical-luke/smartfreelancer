<template>
  <router-link :to="goTo">
    <button
      type="button"
      class="flex h-full justify-center items-center w-full md:w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    >
      <slot></slot>
    </button>
  </router-link>
</template>

<script>

export default {
  name: "LinkButton",
  props: {
    goTo: {
      type: String,
      required: true
    }
  }
};
</script>
