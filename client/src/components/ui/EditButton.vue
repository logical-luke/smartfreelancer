<template>
  <router-link :to="goTo">
    <button
      type="button"
      :class="
        iconOnly
          ? 'text-indigo-500 hover:text-indigo-600'
          : 'inline-flex text-center items-center w-full md:w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200'
      "
    >
      <edit-icon />
      <template v-if="!iconOnly">Edit</template>
    </button>
  </router-link>
</template>

<script>
import EditIcon from "vue-tabler-icons/icons/EditIcon";

export default {
  name: "EditButton",
  components: { EditIcon },
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
