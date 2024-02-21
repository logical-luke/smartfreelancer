<template>
  <clock-play-icon class="mr-2" />
  <div class="w-[4.8rem]">
    <calendar
        v-if="showTime"
        id="start-time"
        v-model="time"
        @update:model-value="updateStartTime"
        dateFormat="&#x200b;"
        timeOnly
    />
    <calendar
        v-if="showCalendar"
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
  name: "StartTime",
  components: {ClockPlayIcon, Datepicker, Calendar},
  data() {
    return {
      time: null,
    };
  },
  props: {
    clockIconMargin: {
      type: Boolean,
      required: false,
      default: false
    },
    startTime: {
      type: Number,
      required: true,
    },
    showTime: {
      type: Boolean,
      required: false,
      default: true
    },
    showCalendar: {
      type: Boolean,
      required: false,
      default: false
    }
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
