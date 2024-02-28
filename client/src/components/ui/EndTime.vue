<template>
  <clock-stop-icon class="mx-2" />
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
import Datepicker from "@vuepic/vue-datepicker";
import Calendar from "primevue/calendar";
import {mapGetters, mapState} from "vuex";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";
import timer from "../../services/timer";
import ClockStopIcon from "vue-tabler-icons/icons/ClockStopIcon";

export default {
  name: "EndTime",
  components: {ClockStopIcon, Calendar},
  data() {
    return {
      time: null,
      manuallySet: false,
    };
  },
  watch: {
    async serverTime() {
      this.setTime(await this.serverTime);
    }
  },
  computed: {
    ...mapState({
      serverTime: (state) => state.time.serverTime,
    }),
    ...mapGetters("settings", ["isManualMode"]),
  },
  methods: {
    setTime(timestamp) {
      if (this.manuallySet) {
        this.time = getDateFromSecondsTimestamp(timestamp);
      } else {

      }
    },
    updateEndTime(endTime) {
      this.manuallySet = true;
      timer.setEndTime(endTime)
    },
  },
  created() {
    this.setTime(this.endTime);
  },
};
</script>

<style scoped></style>
