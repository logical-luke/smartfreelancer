<template>
  <div class="w-[4.1rem]">
    <Datepicker
      v-model="time"
      time-picker
      auto-apply
      @update:model-value="updateStartTime"
      hide-input-icon
      :clearable="false"
      show-now-button
    />
  </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { mapState } from "vuex";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";

export default {
  name: "StartTime",
  components: { Datepicker },
  data() {
    return {
      time: {
        hours: null,
        minutes: null,
      },
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
      serverTime: (state) => state.serverTime,
    }),
  },
  methods: {
    updateStartTime() {
        // store.dispatch("timer/setStartTime", )
    },
    setTime(timestamp) {
      const date = getDateFromSecondsTimestamp(timestamp);
      this.time = {
        hours: date.getHours(),
        minutes: date.getMinutes(),
      };
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
