<template>
  <div class="w-[4.1rem]">
    <Datepicker
      v-model="time"
      time-picker
      auto-apply
      hide-input-icon
      :clearable="false"
      :disabled="disabled"
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
  name: "EndTime",
  components: { Datepicker },
  data() {
    return {
      time: {
        hours: null,
        minutes: null,
      },
      disabled: false,
    };
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      serverTime: (state) => state.serverTime,
    }),
  },
  methods: {
    setTime(timestamp) {
      const date = getDateFromSecondsTimestamp(timestamp);
      this.time = {
        hours: date.getHours(),
        minutes: date.getMinutes(),
      };
    },
    updateTime() {
      this.setTime(this.serverTime);
    },
    setStatus() {
      this.disabled = !!this.timer.id;
    },
  },
  watch: {
    timer() {
      this.setStatus();
    },
    serverTime() {
      this.updateTime();
    },
  },
  mounted() {
    this.setStatus();
  },
};
</script>

<style scoped></style>
