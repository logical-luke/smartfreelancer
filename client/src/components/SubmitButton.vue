<template>
  <button
    class="flex w-full justify-center items-center w-auto px-8 py-4 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
    type="submit"
  >
    <slot name="icon"></slot>
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: "SubmitButton",
};
</script>
