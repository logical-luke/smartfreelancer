<script>
export default {
  name: "DeleteButton",
  props: {
    title: String,
    subject: String,
    iconOnly: Boolean,
  },
  emits: ["delete"],
  methods: {
    showConfirmation() {
      this.$confirm.require({
        message: 'Are you sure you want to delete "' + this.subject + '"?',
        header: "Delete project",
        acceptClass: "confirm-button-accept",
        icon: "pi pi-exclamation-triangle",
        accept: () => {
          this.$emit("delete");
        },
      });
    },
  },
};
</script>

<template>
  <button
      :class="
      iconOnly
        ? 'text-red-500 hover:text-red-600'
        : 'inline-flex text-center items-center w-full md:w-auto font-medium text-sm px-8 py-4 bg-red-400 hover:bg-red-600 rounded transition duration-200'
    "
      @click="showConfirmation"
  >
    <template v-if="!iconOnly">{{ title ? title : "Delete" }}</template>
  </button>
</template>
