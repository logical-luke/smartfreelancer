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
        bg-white
        border-2
        border-indigo-500
        text-black
        hover:bg-indigo-500
        hover:text-white
        rounded-md
        disabled:bg-slate-50
        disabled:text-slate-500
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
  props: {
    type: {
      type: String,
      default: "button",
    },
  }
};
</script>
