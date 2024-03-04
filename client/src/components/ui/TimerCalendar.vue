<template>
  <div>
    <calendar
        id="start-time"
        v-model="time"
        inline showWeek
        @update:model-value="updateStartTime"
        dateFormat="&#x200b;"
    />
  </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import Calendar from "primevue/calendar";
import {mapState} from "vuex";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";
import timer from "../../services/timer";
import ClockPlayIcon from "vue-tabler-icons/icons/ClockPlayIcon";

export default {
  name: "TimerCalendar",
  components: {Datepicker, Calendar},
  data() {
    return {
      time: null,
      startTime: null,
    };
  },
  watch: {
    startTime() {
      this.time = getDateFromSecondsTimestamp(this.startTime);
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
  },
  created() {
    this.time = getDateFromSecondsTimestamp(this.startTime);
  },
};
</script>

<style scoped></style>
