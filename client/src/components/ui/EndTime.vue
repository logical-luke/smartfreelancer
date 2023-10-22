<template>
  <div class="w-[4.3rem]">
    <calendar
        id="end-time"
        v-model="time"
        @update:model-value="updateEndTime"
        :disabled="isDisabled()"
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
  name: "EndTime",
  components: {Datepicker, Calendar},
  data() {
    return {
      time: null,
      loaded: false
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
    updateEndTime(endTime) {
      // timer.setEndTime(endTime)
    },
    setTime(timestamp) {
      this.time = getDateFromSecondsTimestamp(timestamp);
    },
    isDisabled() {
      return this.time === null || !this.loaded || true;
    },
    updateTime() {
      if (this.timer.endTime) {
        this.setTime(this.timer.endTime);

        this.loaded = true;

        return;
      }

      if (this.serverTime) {
        this.setTime(this.serverTime);
      }

      this.loaded = true;
    },
  },
  created() {
    this.time = new Date(new Date().setHours(0,0,0,0));
  },
};
</script>

<style scoped></style>
