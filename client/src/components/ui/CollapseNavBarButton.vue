<template>
  <button
    type="button"
    v-if="slideFinished"
    class="inline-flex bg-indigo-500 text-white items-center justify-center px-1 py-1 border-2 border-indigo-500 rounded-full"
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
import cache from "@/services/cache";

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
    ...mapGetters("settings", ["isNavBarCollapsed"]),
  },
  methods: {
    toggleCollapsed() {
      store.commit("settings/toggleNavBarCollapsed");
      cache.set("navBarCollapsed", this.isNavBarCollapsed ? "1" : "0");
    },
  },
};
</script>
