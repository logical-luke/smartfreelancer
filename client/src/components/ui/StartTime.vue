<template>
  <div class="w-[4.3rem]">
    <calendar
        id="start-time"
        v-model="time"
        @update:model-value="updateStartTime"
        timeOnly
    />
  </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import Calendar from "primevue/calendar";
import {mapState} from "vuex";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";
import timer from "../../services/timer";

export default {
  name: "StartTime",
  components: {Datepicker, Calendar},
  data() {
    return {
      time: null,
    };
  },
  watch: {
    timer() {
      this.updateTime();
    },
    serverTime() {
      this.updateTime();
    },
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      serverTime: (state) => state.time.serverTime,
    }),
  },
  methods: {
    updateStartTime(startTime) {
      timer.setStartTime(startTime)
    },
    setTime(timestamp) {
      this.time = getDateFromSecondsTimestamp(timestamp);
    },
    updateTime() {
      if (this.timer.startTime) {
        this.setTime(this.timer.startTime);

        return;
      }

      if (this.serverTime) {
        this.setTime(this.serverTime);
      }
    },
  },
};
</script>

<style scoped></style>
