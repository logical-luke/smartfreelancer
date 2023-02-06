<template>
  <div class="w-[4.1rem]">
    <Datepicker
      v-model="time"
      time-picker
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
import { ref } from "vue";
import { mapState } from "vuex";


export default {
  name: "EndTime",
  components: { Datepicker },
  computed: {
    ...mapState({
      timer: state => state.timer.current,
      serverTime: state => state.serverTime,
    })
  },
  data() {
    return {
      disabled: false,
      interval: null,
    }
  },
  methods: {
    updateTime(hours, minutes) {
      this.time = {
        hours: hours,
        minutes: minutes,
      }
    },
  },
  watch: {
    timer() {
      this.disabled = !!this.timer.id;
    },
    serverTime() {
      this.updateTime(this.serverTime.hours, this.serverTime.minutes);
    },
  },
  setup() {
    const time = ref({
      hours: new Date().getHours(),
      minutes: new Date().getMinutes()
    });

    return {
      time
    };
  }
};
</script>

<style scoped>

</style>
