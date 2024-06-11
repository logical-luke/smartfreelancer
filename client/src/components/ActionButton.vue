<template>
  <button
      type="button"
      class="inline-flex
        shadow
        px-4
        flex-nowrap
        py-4
        text-sm
        font-medium
        text-white
        bg-indigo-500
        hover:bg-indigo-700
        rounded-md
        transition
        duration-200"
  >
    <span>
      <slot></slot>
    </span>
  </button>
</template>

<script>

export default {
  name: "ActionButton",
};
</script>
