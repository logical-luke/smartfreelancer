<template>
  <div class="w-28" @click="show">
    <InputText
      class="borderless-input placeholder-gray-500"
      placeholder="00:00:00"
      :value="hasDuration ? `${hours}:${minutes}:${seconds}` : ''"
      @update:model-value="updateDuration"
    />
  </div>
  <overlay-panel ref="op" @show="setFocus(true)" @hide="setFocus(false)">
    <div class="flex flex-col">
      <div class="flex items-center justify-center">
        <start-time />
        <end-time />
      </div>
      <div class="flex mt-4 items-center justify-center">
        <timer-calendar />
      </div>
    </div>
  </overlay-panel>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import StartTime from "./timer/StartTime.vue";
import EndTime from "./timer/EndTime.vue";
import OverlayPanel from "primevue/overlaypanel";
import InputText from "primevue/inputtext";
import durationInputToSecondsParser from "@/services/timer/durationInputToSecondsParser";
import timer from "@/services/timer";

export default {
  name: "DurationInput",
  components: { EndTime, StartTime, OverlayPanel, InputText },
  props: {
    includeStartEndTimeButtons: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data() {
    return {
      hours: "00",
      minutes: "00",
      seconds: "00",
      isFocused: false,
      hasDuration: false,
    };
  },
  watch: {
    async serverTime() {
      await this.updateClock();
    },
    async startTime() {
      if (await timer.isTimerMode()) {
        await this.updateClock(true);
      }
    },
    async timer() {
      this.timerRunning = await timer.isTimerRunning();
      await this.updateClock();
      await this.updateIfHasDuration();
    },
    async timerMode() {
      await this.updateIfHasDuration();
    },
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      timerMode: (state) => state.timer.timerMode,
      subjectName: (state) => state.timer.current.subjectName,
      serverTime: (state) => state.time.serverTime,
      startTime: (state) => state.timer.current.startTime,
    }),
    ...mapGetters("time", ["getServerTime"]),
    async startTime() {
      return await timer.getStartTime();
    },
    async endTime() {
      return await timer.setEndTime();
    },
  },
  async mounted() {
    await this.updateClock();
    await this.updateIfHasDuration();
    this.timerRunning = await timer.isTimerRunning();
  },
  methods: {
    async updateClock(forced) {
      if ((await timer.isManualMode()) || !this.isFocused || forced) {
        const timerDuration = await timer.getTimerDurations();
        this.hours = timerDuration.hours;
        this.minutes = timerDuration.minutes;
        this.seconds = timerDuration.seconds;
      }
    },
    async updateIfHasDuration() {
      this.hasDuration =
        ((await timer.isTimerMode()) && (await timer.isTimerRunning())) ||
        (await timer.isManualMode());
    },
    async updateDuration(value) {
      await timer.setDuration(durationInputToSecondsParser(value));
    },
    show(event) {
      this.$refs.op.show(event);
    },
    setFocus(isFocused) {
      this.isFocused = isFocused;
    },
  },
};
</script>

<script setup>
import TimerCalendar from "@/components/timer/TimerCalendar.vue";
</script>
<style scoped>
.borderless-input {
  border: none;
  box-shadow: none;
}

.p-inputtext {
  width: 100%;
  font-weight: 600;
  text-align: center;
  color: black;
}
</style>
