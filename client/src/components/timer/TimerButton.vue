<template>
  <button
      type="button"
      :class="sizeClasses"
      @click.prevent="toggleTimer"
      class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    <player-play-icon :size="Math.ceil(size * 1.8)" v-if="!isRunning"/>
    <player-stop-icon :size="Math.ceil(size * 1.8)" v-else/>
  </button>
</template>

<script>
import PlayerPlayIcon from "vue-tabler-icons/icons/PlayerPlayIcon";
import {mapState} from "vuex";
import PlayerStopIcon from "vue-tabler-icons/icons/PlayerStopIcon";
import timer from "@/services/timer";
import timeEntries from "@/services/timeEntries";

export default {
  name: "TimerButton",
  components: {PlayerStopIcon, PlayerPlayIcon},
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
      isManualMode: false,
    };
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
    }),
    sizeClasses() {
      return `w-${this.size} h-${this.size}`;
    },
  },
  watch: {
    async timer() {
      this.isRunning = await this.checkCurrentTimer();
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
    async checkCurrentTimer() {
      return await timer.isCurrentRunningTimer(
          this.taskId,
          this.projectId,
          this.clientId,
          this.global
      );
    },
  },
  async mounted() {
    this.isRunning = await this.checkCurrentTimer();
  },
};
</script>
