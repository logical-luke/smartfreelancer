<template>
  <clock-stop-icon class="mx-2"/>
  <div class="w-[4.8rem]">
    <calendar
        id="end-time"
        v-model="time"
        @update:model-value="updateEndTime"
        :disabled="!isManualMode"
        dateFormat="&#x200b;"
        timeOnly
    />
  </div>
</template>

<script>
import Calendar from "primevue/calendar";
import {mapState} from "vuex";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";
import timer from "../../services/timer";
import ClockStopIcon from "vue-tabler-icons/icons/ClockStopIcon";

export default {
  name: "EndTime",
  components: {ClockStopIcon, Calendar},
  data() {
    return {
      time: null,
      isManualMode: false,
    };
  },
  watch: {
    async serverTime() {
      this.time = getDateFromSecondsTimestamp(await timer.getEndTime());
    },
    async timerMode() {
      this.isManualMode = await timer.isManualMode();
    },
  },
  computed: {
    ...mapState({
      serverTime: (state) => state.time.serverTime,
      timerMode: (state) => state.timer.timerMode,
    }),
  },
  methods: {
    async updateEndTime(endTime) {
      await timer.setEndTime(endTime)
    },
  },
  async created() {
    this.isManualMode = await timer.isManualMode();
    this.time = getDateFromSecondsTimestamp(await timer.getEndTime());
  },
};
</script>

<style scoped></style>
