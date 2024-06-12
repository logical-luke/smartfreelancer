<template>
  <button
      :type="this.type"
      class="inline-flex
        shadow
        p-4
        min-w-40
        justify-center
        flex-nowrap
        items-center
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
  name: "MainActionButton",
  props: {
    type: {
      type: String,
      default: "button",
    },
  }
};
</script>
