<template>
  <p class="text-gray-300" v-if="!isRunning">00:00:00</p>
  <p v-else>{{ hours }}:{{ minutes }}:{{ seconds }}</p>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import getRelativeTime from "@/services/time/relativeTimeGetter";
import store from "@/store";

export default {
  name: "TimerTime",
  data() {
    return {
      isRunning: false,
      hours: "00",
      minutes: "00",
      seconds: "00",
    };
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
    },
  },
  methods: {
    checkCurrentTimer() {
      return this.timer && this.timer.id;
    },
    getRelativeTime() {
      return getRelativeTime(this.timer.startTime, this.serverTime);
    },
    updateClock() {
      let time = this;
      setInterval(function () {
        if (time.timer.id && store.getters["time/getServerTime"]) {
          const { hours, minutes, seconds } = time.getRelativeTime();
          time.hours = hours;
          time.minutes = minutes;
          time.seconds = seconds;
        } else {
          time.hours = "00";
          time.minutes = "00";
          time.seconds = "00";
        }
      }, 500);
    },
  },
  mounted() {
    this.isRunning = this.checkCurrentTimer();

    this.updateClock();
  },
};
</script>
