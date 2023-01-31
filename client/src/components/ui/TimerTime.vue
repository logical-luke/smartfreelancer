<template>
  <p class="text-gray-300" v-if="!isRunning">00:00:00</p>
  <p v-else>{{ hours }}:{{ minutes }}:{{ seconds }}</p>
</template>

<script>
import { mapState } from "vuex";
import { getRelativeTime } from "@/services/relativeTimeGetter";

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
    }),
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
      return getRelativeTime(new Date(this.timer.startTime * 1000), new Date());
    },
    updateClock() {
      let time = this;
      setInterval(function () {
        if (time.timer.id) {
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
