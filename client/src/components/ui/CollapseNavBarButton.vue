<template>
  <button
    v-if="slideFinished"
    type="button"
    class="inline-flex bg-white text-indigo-500 items-center justify-center items-center px-1 py-1 border-2 border-indigo-500 rounded-full"
    @click.prevent="toggleCollapsed"
  >
    <arrow-down-icon :size="16" v-if="isNavBarCollapsed" />
    <arrow-up-icon :size="16" v-else />
  </button>
</template>

<script>
import { mapGetters } from "vuex";
import ArrowUpIcon from "vue-tabler-icons/icons/ArrowUpIcon";
import store from "@/store";
import ArrowDownIcon from "vue-tabler-icons/icons/ArrowDownIcon";

export default {
  name: "CollapseNavBarButton",
  components: { ArrowDownIcon, ArrowUpIcon },
  props: {
    slideFinished: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    ...mapGetters(["isNavBarCollapsed"]),
  },
  methods: {
    toggleCollapsed() {
      store.commit("toggleNavBarCollapsed");
    },
  },
};
</script>
