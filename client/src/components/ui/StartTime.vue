<template>
  <div class="w-[4.1rem]">
    <Datepicker
      v-model="time"
      time-picker
      hide-input-icon
      :clearable="false"
      show-now-button
    />
  </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref } from "vue";
import { mapState } from "vuex";


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
  computed: {
    ...mapState({
      timer: state => state.timer.current,
      serverTime: state => state.serverTime,
    })
  },
  mounted() {
    if (this.timer.id) {
      this.time.hours = new Date(this.timer.startTime * 1000).getHours();
      this.time.minutes = new Date(this.timer.startTime * 1000).getMinutes();
    } else {
      this.time.hours = new Date(this.serverTime * 1000).getHours();
      this.time.minutes = new Date(this.serverTime * 1000).getMinutes();
    }
  }
};
</script>

<style scoped>

</style>
