<template>
  <button
    type="button"
    class="inline-flex text-center items-center w-full md:w-auto px-6 py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200">
    <edit-icon />
    Edit
  </button>
</template>

<script>
import EditIcon from "vue-tabler-icons/icons/EditIcon";

export default {
  name: "EditButton",
  components: { EditIcon }
};
</script>

<style scoped>

</style>
