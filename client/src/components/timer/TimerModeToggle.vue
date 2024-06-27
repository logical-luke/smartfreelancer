<script>
import ToggleButton from "primevue/togglebutton";
import ClockPlayIcon from "vue-tabler-icons/icons/ClockPlayIcon";
import ClockPlusIcon from "vue-tabler-icons/icons/ClockPlusIcon";
import { mapState } from "vuex";
import timer from "@/services/timer";

export default {
  name: "TimerModeToggle",
  components: { ClockPlusIcon, ClockPlayIcon, ToggleButton },
  props: {
    size: {
      type: Number,
      default: 12,
    },
  },
  data() {
    return {
      checked: false,
      disabled: false,
    };
  },
  watch: {
    async timer() {
      this.disabled = await timer.isTimerRunning();
      this.checked = await timer.isTimerMode();
    },
  },
  methods: {
    async toggle() {
      await timer.toggleTimerMode();
    },
    async isTimerRunning() {
      return await timer.isTimerRunning();
    },
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
    }),
  },
  async created() {
    this.checked = await timer.isTimerMode();
    this.disabled = await timer.isTimerRunning();
  },
};
</script>

<template>
  <toggle-button
    :disabled="this.disabled"
    v-model="checked"
    onLabel="Timer"
    offLabel="Manual Entry"
    @update:model-value="toggle"
  >
    <template #icon>
        <clock-play-icon class="mr-2" :size="Math.ceil(size * 1.9)" v-if="checked" />
        <clock-plus-icon class="mr-2" :size="Math.ceil(size * 1.9)" v-else />
    </template>
  </toggle-button>
</template>

<style>
</style>
