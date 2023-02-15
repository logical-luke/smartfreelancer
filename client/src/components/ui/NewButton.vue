<template>
  <a class="flex items-center">
    <router-link :to="goTo">
      <button
        type="button"
        class="inline-flex text-center items-center w-full md:w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
      >
        <square-plus-icon size="20" />
        New
        <slot></slot>
      </button>
    </router-link>
  </a>
</template>

<script>
import SquarePlusIcon from "vue-tabler-icons/icons/SquarePlusIcon";

export default {
  name: "NewButton",
  components: { SquarePlusIcon },
  props: {
    goTo: {
      type: String,
      required: true,
    },
  },
};
</script>
