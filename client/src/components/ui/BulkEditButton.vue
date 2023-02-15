<template>
  <div>
    <button
      type="button"
      @click="toggleBulk"
      class="inline-flex text-center items-center w-full px-3 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
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
  methods: {
    toggleBulk() {
      this.$emit("toggle-bulk");
    }
  },
  emits: ["toggle-bulk"]
};
</script>
