<template>
  <clock-play-icon class="mx-1" />
  <div class="w-[4.8rem]">
    <calendar
        id="start-time"
        v-model="time"
        @update:model-value="updateStartTime"
        dateFormat="&#x200b;"
        showTime
        :disabled="isDisabled()"
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
      loaded: false
    };
  },
  props: {
    clockIconMargin: {
      type: Boolean,
      required: false,
      default: false
    }
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
    isDisabled() {
      return this.time === null || !this.loaded;
    },
    updateTime() {
      if (this.timer.startTime) {
        this.setTime(this.timer.startTime);

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
