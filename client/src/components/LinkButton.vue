<template>
  <router-link class="flex items-center" :to="goTo">
    <button
        type="button"
        class="inline-flex
          shadow
          px-4
          flex-nowrap
          py-4
          text-sm
          font-medium
          text-white
          bg-indigo-500
          hover:bg-indigo-700
          rounded-md
          transition
          duration-200"
    >
      <span>
        <slot></slot>
      </span>
    </button>
  </router-link>
</template>

<script>

export default {
  name: "LinkButton",
  props: {
    goTo: {
      type: [Object, String],
      required: true,
    },
  },
};
</script>
