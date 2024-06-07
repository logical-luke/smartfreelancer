<template>
  <button
      type="button"
      @click="toggle"
      :class="conditionalClasses"
      class="inline-flex bg-indigo-500 shadow border-indigo-900 border-3 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    <div :class="this.isRunning ? 'motion-safe:animate-pulse' : ''">
      <img v-if="this.isMainIconPomodoro()" src="/tomato-icon.svg" alt="Pomodoro Timer Tomato"/>
      <coffee-icon v-else-if="this.isMainIconBreak()"/>
      <hourglass-icon v-else/>
    </div>
  </button>

  <overlay-panel ref="op" :show-close-icon="false">
    <div class="w-40">
      <div class="flex gap-2 flex-col items-center justify-center">
        <p class="text-lg font-medium text-gray-900">
          {{ this.timeLeft.minutes }}:{{ this.timeLeft.seconds }}
        </p>
        <div class="flex flex-row gap-2">
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
              v-else
              type="button"
              :class="sizeClasses"
              @click="startPomodoro"
              class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
          >
            <player-play-icon/>
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
      <div v-if="this.configuration.settingsExpanded" class="flex-col mt-4 gap-2 items-center justify-center">
        <div class="text-center mx-auto flex-row items-center justify-center">
          <button
              type="button"
              @click="setPomodoroType"
              :class="pomodoroIconConditionalClasses"
              class="inline-flex mx-2 bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm
      font-medium text-white rounded-full transition duration-200"
          >
            <img src="/tomato-icon.svg" alt="Pomodoro Timer Tomato"/>
          </button>
          <button
              type="button"
              @click="setFixedType"
              :class="hourglassIconConditionalClasses"
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
              v-model="this.configuration.workDuration"
              @update:model-value="this.pushConfiguration()"
              :allowEmpty="false"
          />
          {{ $t("min") }}
          <div class="flex mt-2 space-x-4">
            <p v-for="n in [20, 25, 30, 55]"
               :key="n"
               :class="{ 'font-bold': this.configuration.workDuration === n }"
               @click="this.setWorkDuration(n)">{{ n }}
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
              v-model="this.configuration.breakDuration"
              @update:model-value="this.pushConfiguration()"
              :allowEmpty="false"
          />
          {{ $t("min") }}
          <div class="flex mt-2 space-x-4">
            <div class="flex mt-2 space-x-4">
              <p
                  v-for="n in [5, 6, 10]"
                  :key="n"
                  :class="{ 'font-bold': this.configuration.breakDuration === n }"
                  @click="this.setBreakDuration(n)">{{ n }}
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
              v-model="this.configuration.repeat"
              @update:model-value="this.pushConfiguration()"
              :allowEmpty="false"
          />
          {{ $t("times") }}
          <div class="flex mt-2 space-x-4">
            <div class="flex mt-2 space-x-4">
              <p
                  v-for="n in [2, 4, 6]"
                  :key="n"
                  :class="{ 'font-bold': this.configuration.repeat === n }"
                  @click="this.setRepeat(n)">{{ n }}
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
import CoffeeIcon from "vue-tabler-icons/icons/CoffeeIcon";

export default {
  name: "PomodoroTimer",
  components: {
    CoffeeIcon,
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
    async serverTime() {
      await this.updateTimeLeft();

      if (this.isRunning && await pomodoro.hasPomodoroEnded()) {
        await pomodoro.pickNextPomodoro();
      }
    },
    async pomodoro() {
      this.isRunning = await pomodoro.isRunning();
    }
  },
  computed: {
    sizeClasses() {
      return `w-${this.size} h-${this.size}`;
    },
    conditionalClasses() {
      return this.sizeClasses;
    },
    pomodoroIconConditionalClasses() {
      return this.sizeClasses + ' ' + (this.isPomodoroTypeSetInSettings() ? 'bg-indigo-600' : '');
    },
    hourglassIconConditionalClasses() {
      return this.sizeClasses + ' ' + (this.isPomodoroTypeSetInSettings() ? '' : 'bg-indigo-600');
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
      configuration: {
        workDuration: 25,
        breakDuration: 5,
        repeat: 4,
        type: 'pomodoro',
        settingsExpanded: false,
      },
      timeLeft: {
        hours: "00",
        minutes: "00",
        seconds: "00"
      },
      isRunning: false,
    };
  },
  methods: {
    toggle(event) {
      this.$refs.op.toggle(event);
    },
    async toggleSettingsExpanded() {
      this.configuration.settingsExpanded = !this.configuration.settingsExpanded;
      await this.pushConfiguration();
    },
    async startPomodoro() {
      if (await pomodoro.doesCurrentPomodoroExist()) {
        await pomodoro.resumePomodoro();
      }

      await pomodoro.startPomodoro(
          this.configuration.workDuration * 60,
          this.configuration.breakDuration * 60,
          this.configuration.repeat,
          this.configuration.type
      );
    },
    async pausePomodoro() {
      await pomodoro.pausePomodoro();
    },
    async updateTimeLeft() {
      this.timeLeft = await pomodoro.getDurations();
    },
    setPomodoroType() {
      this.configuration.type = 'pomodoro';
    },
    setFixedType() {
      this.configuration.type = 'fixed';
    },
    isPomodoroTypeSetInSettings() {
      return this.configuration.type === 'pomodoro';
    },
    isMainIconPomodoro() {
      return (this.pomodoro && this.pomodoro.type === 'pomodoro') || (!this.pomodoro && this.configuration.type === 'pomodoro');
    },
    isMainIconBreak() {
      return this.pomodoro && this.pomodoro.type === 'break';
    },
    async setWorkDuration(value) {
        this.configuration.workDuration = value;
        await this.pushConfiguration();
    },
    async setBreakDuration(value) {
        this.configuration.breakDuration = value;
        await this.pushConfiguration();
    },
    async setRepeat(value) {
        this.configuration.repeat = value;
        await this.pushConfiguration();
    },
    async pushConfiguration() {
      const configuration = JSON.parse(JSON.stringify(this.configuration));
      await pomodoro.updateConfiguration(configuration);
    },
  },
  async mounted() {
    await this.updateTimeLeft();
    this.isRunning = await pomodoro.isRunning();
    this.configuration = await pomodoro.getConfiguration();
  },
};
</script>

<style scoped>

</style>
