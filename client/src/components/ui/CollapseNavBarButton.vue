<template>
  <button
    type="button"
    class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
    @click.prevent="toggleCollapsed"
  >
    <arrow-down-icon :size="12" v-if="isNavBarCollapsed" />
    <arrow-up-icon v-else />
  </button>
</template>

<script>
import PlayerPlayIcon from "vue-tabler-icons/icons/PlayerPlayIcon";
import { mapActions, mapGetters, mapState } from "vuex";
import PlayerStopIcon from "vue-tabler-icons/icons/PlayerStopIcon";
import ArrowUpIcon from "vue-tabler-icons/icons/ArrowUpIcon";
import store from "@/store";
import ArrowDownIcon from "vue-tabler-icons/icons/ArrowDownIcon";

export default {
  name: "CollapseNavBarButton",
  components: { ArrowDownIcon, ArrowUpIcon },
  computed: {
    ...mapGetters(["isNavBarCollapsed"])
  },
  methods: {
    toggleCollapsed() {
      store.commit("toggleNavBarCollapsed");
    }
  },
};
</script>
