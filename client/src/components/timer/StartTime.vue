<template>
  <clock-play-icon class="mr-2" />
  <div class="w-[4.8rem]">
    <calendar
      id="start-time"
      v-model="time"
      @update:model-value="updateStartTime"
      dateFormat="&#x200b;"
      timeOnly
    />
  </div>
</template>

<script>
import Calendar from "primevue/calendar";
import { mapState } from "vuex";
import getDateFromSecondsTimestamp from "@/services/time/getDateFromSecondsTimestamp";
import timer from "../../services/timer";
import ClockPlayIcon from "vue-tabler-icons/icons/ClockPlayIcon";

export default {
  name: "StartTime",
  components: { ClockPlayIcon, Calendar },
  data() {
    return {
      time: null,
    };
  },
  watch: {
    async serverTime() {
      this.time = getDateFromSecondsTimestamp(await timer.getStartTime());
    },
  },
  computed: {
    ...mapState({
      serverTime: (state) => state.time.serverTime,
    }),
  },
  methods: {
    updateStartTime(startTime) {
      timer.setStartTime(startTime);
    },
  },
  async created() {
    this.time = getDateFromSecondsTimestamp(await timer.getStartTime());
  },
};
</script>

<style scoped></style>
