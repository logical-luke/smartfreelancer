<template>
  <button
    type="button"
    :class="sizeClasses"
    class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
    @click.prevent="toggleTimer"
  >
    <player-play-icon :size="Math.ceil(size * 1.8)" v-if="!isRunning" />
    <player-stop-icon :size="Math.ceil(size * 1.8)" v-else />
  </button>
</template>

<script>
import PlayerPlayIcon from "vue-tabler-icons/icons/PlayerPlayIcon";
import { mapActions, mapState } from "vuex";
import PlayerStopIcon from "vue-tabler-icons/icons/PlayerStopIcon";
import timer from "@/services/timer";

export default {
  name: "ToggleTimerButton",
  components: { PlayerStopIcon, PlayerPlayIcon },
  props: {
    projectId: {
      type: String,
    },
    clientId: {
      type: String,
    },
    taskId: {
      type: String,
    },
    size: {
      type: Number,
      default: 12,
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
    }),
    ...mapActions("timer", ["stopTimer"]),
    sizeClasses() {
      return `w-${this.size} h-${this.size}`;
    },
  },
  watch: {
    timer() {
      this.isRunning = this.checkCurrentTimer();
    },
  },
  methods: {
    async toggleTimer() {
      if (!this.isRunning && this.timer && this.timer.id) {
        await timer.stopTimer();
      }

      if (this.isRunning) {
        await timer.stopTimer();
      } else {
        await timer.startTimer();

        if (this.projectId && this.timer.projectId !== this.projectId) {
          await this.$store.dispatch("timer/setProjectId", this.projectId);
        }

        if (this.clientId && this.timer.clientId !== this.clientId) {
          await this.$store.dispatch("timer/setClientId", this.clientId);
        }

        if (this.taskId && this.timer.taskId !== this.taskId) {
          await this.$store.dispatch("timer/setTaskId", this.taskId);
        }
      }
    },
    checkCurrentTimer() {
      return (
        (this.timer &&
          this.timer.startTime &&
          this.projectId &&
          this.timer.projectId === this.projectId) ||
        (this.taskId && this.timer.taskId === this.taskId) ||
        (this.clientId && this.timer.clientId === this.clientId) ||
        (this.timer.id && this.global)
      );
    },
  },
  mounted() {
    this.isRunning = this.checkCurrentTimer();
  },
};
</script>
