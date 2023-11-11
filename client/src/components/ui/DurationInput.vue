<template>
  <div @click="show" class="w-28">
    <input-text
        class="borderless-input"
        placeholder="00:00:00"
        :value="isRunning ? `${hours}:${minutes}:${seconds}` : '' "
        @update:model-value="updateDuration"
    />
  </div>
  <overlay-panel
      ref="op"
      @show="setFocus(true)"
      @hide="setFocus(false)"
  >
    <div class="flex items-center justify-center">
      <start-time :start-time="this.startTime" />
      <end-time :end-time="this.endTime" />
    </div>
  </overlay-panel>
</template>

<style scoped>
.borderless-input {
  border: none;
  box-shadow: none;
}

 .p-inputtext {
   width: 100%;
   font-weight: 500;
   text-align:center;
 }
</style>

<script>
import { mapGetters, mapState } from "vuex";
import getRelativeTime from "@/services/time/relativeTimeGetter";
import store from "@/store";
import StartTime from "./StartTime.vue";
import EndTime from "./EndTime.vue";
import OverlayPanel from 'primevue/overlaypanel';
import InputText from 'primevue/inputtext';
import durationInputToSecondsParser from "../../services/timer/durationInputToSecondsParser";
import timer from "../../services/timer";

export default {
  name: "DurationInput",
  components: {EndTime, StartTime, OverlayPanel, InputText},
  data() {
    return {
      isRunning: false,
      hours: "00",
      minutes: "00",
      seconds: "00",
      isFocused: false,
      startTime: null,
      endTime: null,
    };
  },
  props: {
    includeStartEndTimeButtons: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      subjectName: (state) => state.timer.current.subjectName,
      serverTime: (state) => state.time.serverTime,
    }),
    ...mapGetters("time", ["getServerTime"])
  },
  watch: {
    timer() {
      this.isRunning = this.checkCurrentTimer();
      this.updateStartTime();
      this.updateEndTime();
    },
    serverTime() {
      this.updateStartTime();
      this.updateEndTime();
    },
  },
  methods: {
    checkCurrentTimer() {
      return this.timer && this.timer.id;
    },
    getRelativeTime() {
      return getRelativeTime(this.timer.startTime, this.serverTime);
    },
    updateStartTime() {
      if (this.timer.startTime) {
        this.startTime = this.timer.startTime;

        return;
      }

      if (this.serverTime) {
        this.startTime = this.serverTime;
      }
    },
    updateEndTime() {
      if (this.serverTime) {
        this.endTime = this.serverTime;
      }
    },
    updateClock() {
      let time = this;
      setInterval(function () {
        if (!time.isFocused) {
          if (time.timer.id && store.getters["time/getServerTime"]) {
            const {hours, minutes, seconds} = time.getRelativeTime();
            time.hours = hours;
            time.minutes = minutes;
            time.seconds = seconds;
          } else {
            time.hours = "00";
            time.minutes = "00";
            time.seconds = "00";
          }
        }
      }, 500);
    },
    async updateDuration(value) {
      await timer.adjustStartTimeUsingDurationSeconds(durationInputToSecondsParser(value));
    },
    show(event) {
      this.$refs.op.show(event);
    },
    setFocus(isFocused) {
      this.isFocused = isFocused;
    },
  },
  mounted() {
    this.isRunning = this.checkCurrentTimer();
    this.updateStartTime();
    this.updateEndTime();

    this.updateClock();
  },
};
</script>
<script setup>
</script>
