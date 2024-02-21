<script>
import ToggleButton from 'primevue/togglebutton';
import ClockPlayIcon from "vue-tabler-icons/icons/ClockPlayIcon";
import ClockPlusIcon from "vue-tabler-icons/icons/ClockPlusIcon";
import store from "@/store";
import cache from "@/services/cache";
import {mapGetters} from "vuex";

export default {
  name: "TimerModeToggle",
  components: {ClockPlusIcon, ClockPlayIcon, ToggleButton },
  data() {
    return {
      checked: false
    }
  },
  methods: {
    toggle() {
      store.commit("settings/toggleTimerMode");
      cache.set("timerMode", this.timerMode);
    }
  },
  computed: {
    ...mapGetters("settings", ["getTimerMode"]),
  },
  created() {
    this.checked = this.getTimerMode === 'timer';
  }
}
</script>

<template>
  <toggle-button v-model="checked" onLabel=" " offLabel=" "
                 unstyled
                 @update:model-value="toggle"
                 class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200">
    <template #icon>
      <clock-play-icon v-if="checked" />
      <clock-plus-icon v-else />
    </template>
  </toggle-button>
</template>

<style scoped>

</style>
