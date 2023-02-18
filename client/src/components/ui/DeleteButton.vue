<template>
  <button
    @click="showConfirmation"
    :class="
      iconOnly
        ? 'text-red-500 hover:text-red-600'
        : 'inline-flex text-center items-center w-full md:w-auto font-medium text-sm px-6 py-3 bg-red-400 hover:bg-red-600 rounded transition duration-200'
    "
  >
    <trash-icon />
    <template v-if="!iconOnly">{{ title ? title : "Delete" }}</template>
  </button>
</template>

<script>
import TrashIcon from "vue-tabler-icons/icons/TrashIcon";

export default {
  name: "DeleteButton",
  components: { TrashIcon },
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

<style scoped></style>
