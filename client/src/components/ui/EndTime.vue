<template>
  <clock-stop-icon class="mx-2" />
  <div class="w-[4.8rem]">
    <calendar
        id="end-time"
        v-model="time"
        :disabled="true"
        dateFormat="&#x200b;"
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
import ClockStopIcon from "vue-tabler-icons/icons/ClockStopIcon";

export default {
  name: "EndTime",
  components: {ClockStopIcon, Datepicker, Calendar},
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
    endTime: {
      type: Number,
      required: true,
    }
  },
  watch: {
    endTime() {
     this.setTime(this.endTime);
    }
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      serverTime: (state) => state.time.serverTime,
    }),
  },
  methods: {
    setTime(timestamp) {
      this.time = getDateFromSecondsTimestamp(timestamp);
    },
  },
  created() {
    this.setTime(this.endTime);
  },
};
</script>

<style scoped></style>
