<template>
  <li>
    <router-link
      class="flex items-center pl-3 py-3 pr-4 hover:bg-gray-900 text-gray-50 rounded"
      :to="goTo"
      active-class="bg-indigo-500"
      exact
    >
      <span class="flex justify-center items-center mr-3">
        <slot name="icon"></slot>
      </span>
      <span><slot name="title"></slot></span>
    </router-link>
  </li>
</template>

<script>
export default {
  name: "SidebarItem",
  props: {
    goTo: {
      type: String,
      required: true,
    },
  },
};
</script>
