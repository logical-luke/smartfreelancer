<template>
  <div>
    <calendar
        id="start-time"
        v-model="time"
        inline
        :pt="{
          clearButton: {
            root: 'invisible'
          }
        }"
        selectionMode="range" :manualInput="false"
        showButtonBar
        :maxDate="this.maxDate"
        @update:model-value="updateTimer"
        dateFormat="&#x200b;"
    />
  </div>
</template>

<script>
import Calendar from "primevue/calendar";
import {mapState} from "vuex";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";
import timer from "../../services/timer";
import ClockPlayIcon from "vue-tabler-icons/icons/ClockPlayIcon";
import getSecondsTimestampFromDate from "@/services/time/getSecondsTimestampFromDate";

export default {
  name: "TimerCalendar",
  components: {Calendar},
  data() {
    return {
      time: null,
      maxDate: null,
    };
  },
  watch: {
    async timer() {
      this.setTime(await timer.getStartTime(), await timer.getEndTime());
    },
    async serverTime() {
      await this.updateMaxDate();
    }
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      serverTime: (state) => state.time.serverTime,
    }),
  },
  methods: {
    async updateTimer(time) {
      if (await timer.isManualMode()
          && time[1] !== null
          && time[0].getTime() > time[1].getTime()
      ) {
        const temp = time[0];
        time[0] = time[1];
        time[1] = temp;
      }
      await timer.adjustDays(time[0], time[1])
    },
    setTime(startTimeTimestamp, endTimeTimestamp) {
      this.time = [
        getDateFromSecondsTimestamp(startTimeTimestamp),
        getDateFromSecondsTimestamp(endTimeTimestamp),
      ];
    },
    async updateMaxDate() {
      if (await timer.isManualMode()) {
        return this.maxDate = null;
      }

      return this.maxDate = new Date();
    }
  },
  async created() {
    this.setTime(await timer.getStartTime(), await timer.getEndTime());
    await this.updateMaxDate();
  },
};
</script>

<style scoped></style>
