<template>
  <router-link class="flex items-center" :to="goTo">
    <button
      type="button"
      class="inline-flex border-3 border-indigo-900 shadow text-center items-center w-full px-3 flex-nowrap py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    >
      <slot name="icon"><plus-icon size="20" /></slot>
      <span class="ml-2">
        Add
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
