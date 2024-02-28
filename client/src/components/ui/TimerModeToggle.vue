<script>
import ToggleButton from 'primevue/togglebutton';
import ClockPlayIcon from "vue-tabler-icons/icons/ClockPlayIcon";
import ClockPlusIcon from "vue-tabler-icons/icons/ClockPlusIcon";
import { mapState} from "vuex";
import timer from "@/services/timer";

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
      checked: false,
      disabled: false,
    }
  },
  watch: {
    async timer() {
      this.disabled = await timer.isTimerRunning();
    }
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
    ...mapState("timer", ["current"]),
  },
  async created() {
    this.checked = await timer.isTimerMode();
    this.disabled = await timer.isTimerRunning();
    console.log(this.disabled);
  }
}
</script>

<template>
  <toggle-button :disabled="this.disabled" v-model="checked" onLabel=" " offLabel=" "
                 unstyled
                 @update:model-value="toggle"
                 :class="{'bg-gray-500 hover:bg-gray-600': this.disabled,
                        'bg-indigo-500 hover:bg-indigo-600': !this.disabled}"
                 class="inline-flex items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200">
    <template #icon>
      <clock-play-icon :size="Math.ceil(size * 1.9)" v-if="checked"/>
      <clock-plus-icon :size="Math.ceil(size * 1.9)" v-else/>
    </template>
  </toggle-button>
</template>

<style scoped>

</style>
