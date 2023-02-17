<template>
  <div>
    <button
      type="button"
      @click="toggleBulk"
      :class="active ? 'border-indigo-500 border' : 'text-white bg-indigo-500 hover:bg-indigo-600'"
      class="inline-flex text-center items-center w-full px-3 py-3 text-sm font-medium rounded transition duration-200"
    >
      <list-details-icon size="20" />
      <span class="ml-2">
      Bulk edit
    </span>
    </button>
  </div>
</template>

<script>
import ListDetailsIcon from "vue-tabler-icons/icons/ListDetailsIcon";

export default {
  name: "BulkEditButton",
  components: { ListDetailsIcon },
  props: {
    active: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    toggleBulk() {
      this.$emit("toggle-bulk");
    }
  },
  emits: ["toggle-bulk"]
};
</script>
