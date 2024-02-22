<script>
import ToggleButton from 'primevue/togglebutton';
import ClockPlayIcon from "vue-tabler-icons/icons/ClockPlayIcon";
import ClockPlusIcon from "vue-tabler-icons/icons/ClockPlusIcon";
import store from "@/store";
import cache from "@/services/cache";
import {mapGetters, mapState} from "vuex";

export default {
  name: "TimerModeToggle",
  components: {ClockPlusIcon, ClockPlayIcon, ToggleButton},
  props: {
    size: {
      type: Number,
      default: 12,
    },
  },
  data() {
    return {
      checked: false
    }
  },
  methods: {
    toggle() {
      store.commit("settings/toggleTimerMode");
      cache.set("timerMode", this.getTimerMode);
    },
    isTimerRunning() {
      return this.timer && this.timer.id && this.timer.startTime;
    },
  },
  computed: {
    ...mapGetters("settings", ["getTimerMode", "isTimerMode"]),
    ...mapState({
      timer: (state) => state.timer.current,
    }),
  },
  created() {
    this.checked = this.isTimerMode;
  }
}
</script>

<template>
  <toggle-button :disabled="this.isTimerRunning()" v-model="checked" onLabel=" " offLabel=" "
                 unstyled
                 @update:model-value="toggle"
                 :class="{'bg-gray-500 hover:bg-gray-600': this.isTimerRunning(),
                        'bg-indigo-500 hover:bg-indigo-600': !this.isTimerRunning()}"
                 class="inline-flex items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200">
    <template #icon>
      <clock-play-icon :size="Math.ceil(size * 1.9)" v-if="checked"/>
      <clock-plus-icon :size="Math.ceil(size * 1.9)" v-else/>
    </template>
  </toggle-button>
</template>

<style scoped>

</style>
