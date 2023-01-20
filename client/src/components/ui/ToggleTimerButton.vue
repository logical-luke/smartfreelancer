<template>
  <button
    type="button"
    class="w-12 h-12 inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
    @click.prevent="toggleTimer"
  >
    <player-play-icon v-if="!isRunning"  />
    <player-stop-icon v-else />
  </button>
</template>

<script>
import PlayerPlayIcon from "vue-tabler-icons/icons/PlayerPlayIcon";
import { mapState } from "vuex";
import PlayerStopIcon from "vue-tabler-icons/icons/PlayerStopIcon";
import api from "@/api/api";

export default {
  name: "ToggleTimerButton",
  components: { PlayerStopIcon, PlayerPlayIcon },
  props: {
      projectId: {
          type: Number,
      },
      global: {
          type: Boolean,
      }
  },
  data() {
    return {
      isRunning: false,
    };
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
    })
  },
  watch: {
    timer() {
      this.isRunning = this.checkCurrentTimer();
    }
  },
  methods: {
    async toggleTimer() {
      if (!this.isRunning && this.timer.id) {
        await this.stopTimer();
      }

      if (this.isRunning) {
        await this.stopTimer();
      } else {
        await this.startTimer();
      }
    },
    checkCurrentTimer() {
      return (this.timer.projectId === this.projectId)
      || (this.timer.id && this.global);
    },
    async startTimer() {
      const timerPayload = {
        projectId: this.projectId
      };
      console.log(timerPayload);
      const timer = await api.createTimer(timerPayload);

      this.$store.commit("timer/setTimer", timer)
    },
    async stopTimer() {
      const timer = await api.stopTimer();

      this.$store.commit("timer/clearTimer")
    }
  },
  mounted() {
    this.isRunning = this.checkCurrentTimer();
  }
};
</script>

