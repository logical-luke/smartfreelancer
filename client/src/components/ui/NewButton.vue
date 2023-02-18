<template>
  <router-link class="flex items-center" :to="goTo">
    <button
      type="button"
      class="inline-flex text-center items-center w-full px-3 flex-nowrap py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    >
      <plus-icon size="20" />
      <span class="ml-2">
        New
        <slot></slot>
      </span>
    </button>
  </router-link>
</template>

<script>
import PlusIcon from "vue-tabler-icons/icons/PlusIcon";

export default {
  name: "NewButton",
  components: { PlusIcon },
  props: {
    goTo: {
      type: String,
      required: true,
    },
  },
};
</script>
