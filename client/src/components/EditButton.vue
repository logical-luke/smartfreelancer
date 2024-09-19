<template>
  <router-link :to="goTo">
    <button
      type="button"
      :class="
        iconOnly
          ? 'text-indigo-500 hover:text-indigo-600'
          : 'inline-flex text-center items-center w-full md:w-auto px-8 py-4 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200'
      "
    >
      <template v-if="!iconOnly">Edit</template>
    </button>
  </router-link>
</template>

<script>

export default {
  name: "EditButton",
  props: {
    goTo: {
      type: String,
      required: true,
    },
    iconOnly: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style scoped></style>
