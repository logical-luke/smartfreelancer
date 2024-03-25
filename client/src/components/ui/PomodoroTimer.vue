<template>
  <button
      type="button"
      @click="toggle"
      :class="sizeClasses"
      class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    <img src="/tomato-icon.svg" alt="Pomodoro Timer Tomato"/>
  </button>

  <overlay-panel ref="op" :show-close-icon="false">
    <div class="w-40">
      <div class="flex gap-2 flex-col items-center justify-center">
        <p class="text-lg font-medium text-gray-900">
          {{ this.timeLeft.minutes }}:{{ this.timeLeft.seconds }}
        </p>
        <div class="flex flex-row gap-2">
          <button
              v-if="!this.isRunning"
              type="button"
              :class="sizeClasses"
              @click="startPomodoro"
              class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
          >
            <player-play-icon/>
          </button>
          <button
              v-if="this.isRunning"
              type="button"
              :class="sizeClasses"
              @click="pausePomodoro"
              class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
          >
            <player-pause-icon/>
          </button>
          <button
              type="button"
              :class="sizeClasses"
              @click.prevent="this.toggleSettingsExpanded()"
              class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
          >
            <settings-icon/>
          </button>
          <button
              type="button"
              :class="sizeClasses"
              class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
          >
            <rotate-icon/>
          </button>
        </div>
      </div>
      <div v-if="settingsExpanded" class="flex-col mt-4 gap-2 items-center justify-center">
        <div class="text-center mx-auto flex-row items-center justify-center">
          <button
              type="button"
              @click="toggle"
              :class="sizeClasses"
              class="inline-flex mx-2 bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm
      font-medium text-white rounded-full transition duration-200"
          >
            <img src="/tomato-icon.svg" alt="Pomodoro Timer Tomato"/>
          </button>
          <button
              type="button"
              @click="toggle"
              :class="sizeClasses"
              class="inline-flex mx-2 bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm
       font-medium text-white rounded-full transition duration-200"
          >
            <hourglass-icon/>
          </button>
        </div>
        <div class="flex-auto my-2">
          <label for="work-duration" class="font-bold block mb-2"> {{ $t("Work duration") }}: </label>
          <input-number
              :min="1"
              inputId="work-duration"
              showButtons
              inputClass="w-16"
              :useGrouping="false"
              :step="1"
              v-model="workDuration"
              :allowEmpty="false"
          />
          {{ $t("min") }}
          <div class="flex mt-2 space-x-4">
            <p v-for="n in [20, 25, 30, 55]"
               :key="n"
               :class="{ 'font-bold': this.workDuration === n }"
               @click="this.workDuration = n">{{n}}
            </p>
          </div>
        </div>
        <div class="flex-auto my-2">
          <label for="break-duration" class="font-bold block mb-2"> {{ $t("Break") }}: </label>
          <input-number
              :min="0"
              inputId="break-duration"
              :step="1"
              inputClass="w-16"
              :useGrouping="false"
              showButtons
              v-model="breakDuration"
              :allowEmpty="false"
          />
          {{ $t("min") }}
          <div class="flex mt-2 space-x-4">
            <div class="flex mt-2 space-x-4">
              <p
                  v-for="n in [5, 6, 10]"
                  :key="n"
                  :class="{ 'font-bold': this.breakDuration === n }"
                  @click="this.breakDuration = n">{{n}}
              </p>
            </div>
          </div>
        </div>
        <div class="flex-auto my-2">
          <label for="repeat" class="font-bold block mb-2"> {{ $t("Repeat") }}: </label>
          <input-number
              :min="0"
              inputId="repeat"
              :step="1"
              inputClass="w-16"
              :useGrouping="false"
              showButtons
              v-model="repeat"
              :allowEmpty="false"
          />
          {{ $t("times") }}
          <div class="flex mt-2 space-x-4">
            <div class="flex mt-2 space-x-4">
              <p
                  v-for="n in [2, 4, 6]"
                  :key="n"
                  :class="{ 'font-bold': this.repeat === n }"
                  @click="this.repeat = n">{{n}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </overlay-panel>
</template>j

<script>
import OverlayPanel from "primevue/overlaypanel";
import SelectButton from "primevue/selectbutton";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import HourglassEmptyIcon from "vue-tabler-icons/icons/HourglassEmptyIcon";
import PlayerPlayIcon from "vue-tabler-icons/icons/PlayerPlayIcon";
import SettingsIcon from "vue-tabler-icons/icons/SettingsIcon";
import RotateIcon from "vue-tabler-icons/icons/RotateIcon";
import HourglassIcon from "vue-tabler-icons/icons/HourglassIcon";
import pomodoro from "@/services/pomodoro";
import {mapState} from "vuex";
import PlayerPauseIcon from "vue-tabler-icons/icons/PlayerPauseIcon";

export default {
  name: "PomodoroTimer",
  components: {
    PlayerPauseIcon,
    HourglassIcon,
    RotateIcon,
    SettingsIcon,
    PlayerPlayIcon,
    HourglassEmptyIcon,
    OverlayPanel,
    SelectButton,
    InputText,
    InputNumber,
  },
  watch: {
    serverTime() {
      this.updateTimeLeft();
    },
    async pomodoro() {
      this.isRunning = await pomodoro.isRunning()
    }
  },
  computed: {
    sizeClasses() {
      return `w-${this.size} h-${this.size}`;
    },
    ...mapState({
      serverTime: (state) => state.time.serverTime,
      pomodoro: (state) => state.pomodoro.current,
    })
  },
  props: {
    size: {
      type: Number,
      default: 12,
    },
  },
  data() {
    return {
      mode: 'pomodoro',
      workDuration: 25,
      breakDuration: 5,
      repeat: 4,
      timeLeft: {
        hours: "00",
        minutes: "00",
        seconds: "00"
      },
      settingsExpanded: false,
      isRunning: false,
    };
  },
  methods: {
    toggle(event) {
      this.$refs.op.toggle(event);
    },
    toggleSettingsExpanded() {
      this.settingsExpanded = !this.settingsExpanded;
    },
    async startPomodoro() {
      await pomodoro.startPomodoro(
          this.workDuration,
          this.breakDuration,
          this.repeat,
          this.mode
      );
    },
    async pausePomodoro() {
      // await pomodoro.startPomodoro(this.workDuration, this.breakDuration, this.repeat);
    },
    async updateTimeLeft() {
      this.timeLeft = await pomodoro.getDurations();
    }
  },
  async mounted() {
    await this.updateTimeLeft();
    this.isRunning = await pomodoro.isRunning();
  },
};
</script>

<style scoped></style>
