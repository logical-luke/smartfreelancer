<template>
  <button
    type="button"
    class="w-12 h-12 inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
    @click.prevent="toggleTimer"
  >
    <player-play-icon v-if="!isRunning" />
    <player-stop-icon v-else />
  </button>
</template>

<script>
import PlayerPlayIcon from "vue-tabler-icons/icons/PlayerPlayIcon";
import { mapActions, mapState } from "vuex";
import PlayerStopIcon from "vue-tabler-icons/icons/PlayerStopIcon";

export default {
  name: "ToggleTimerButton",
  components: { PlayerStopIcon, PlayerPlayIcon },
  props: {
    projectId: {
      type: Number,
    },
    global: {
      type: Boolean,
    },
  },
  data() {
    return {
      isRunning: false,
    };
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      timerProjectId: (state) => state.timer.current.projectId,
    }),
    ...mapActions('timer', ['stopTimer']),
  },
  watch: {
    timer() {
      this.isRunning = this.checkCurrentTimer();
    },
    timerProjectId() {
      this.isRunning = this.checkCurrentTimer();
    },
  },
  methods: {
    async toggleTimer() {
      if (!this.isRunning && this.timer && this.timer.id) {
        await this.stopTimer();
      }

      if (this.isRunning) {
        await this.$store.dispatch("timer/stopTimer");
      } else {
        await this.startTimer();
      }
    },
    checkCurrentTimer() {
      return (
        (this.timer &&
          this.timer.startTime &&
          this.timerProjectId === this.projectId) ||
        (this.timer.id > 0 && this.global)
      );
    },
    async startTimer() {
      if (this.projectId && this.timer.projectId !== this.projectId) {
        await this.$store.commit("timer/setProjectId", this.projectId);
      }

      await this.$store.dispatch("timer/startTimer");
    },
  },
  mounted() {
    this.isRunning = this.checkCurrentTimer();
  },
};
</script>
