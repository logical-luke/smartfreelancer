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
              type="button"
              :class="sizeClasses"
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
      <div v-if="settingsExpanded" class="flex-col mt-4 gap-2 items-center justify-center">
        <div class="text-center mx-auto flex-row items-center justify-center">
          <button
              type="button"
              @click="toggle"
              :class="sizeClasses"
              class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
          >
            <img src="/tomato-icon.svg" alt="Pomodoro Timer Tomato"/>
          </button>
          <button
              type="button"
              @click="toggle"
              :class="sizeClasses"
              class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
          >
            <hourglass-icon/>
          </button>
        </div>
        <div class="flex-auto mt-2">
          <label for="work-duration" class="font-bold block mb-2"> {{ $t("Work duration") }}: </label>
          <input-number
              :min="1"
              inputId="work-duration"
              showButtons
              inputClass="w-16"
              :step="1"
              v-model="workDuration"
              placeholder="25"
          />
          {{ $t("min") }}

        </div>
        <div class="flex-auto">
          <label for="break-duration" class="font-bold block mb-2"> {{ $t("Break") }}: </label>
          <input-number
              :min="0"
              inputId="break-duration"
              :step="1"
              inputClass="w-16"
              showButtons
              v-model="breakDuration"
              placeholder="5"
          />
          {{ $t("min") }}
        </div>
        <div class="flex-auto">
          <label for="repeat" class="font-bold block mb-2"> {{ $t("Repeat") }}: </label>
          <input-number
              :min="0"
              inputId="repeat"
              :step="1"
              inputClass="w-16"
              showButtons
              v-model="repeat"
              placeholder="4"
          />
          {{ $t("times") }}
        </div>
      </div>
    </div>
  </overlay-panel>
</template>

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

export default {
  name: "PomodoroTimer",
  components: {
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
  computed: {
    sizeClasses() {
      return `w-${this.size} h-${this.size}`;
    },
    modes() {
      return [
        {name: this.$t("Pomodoro"), code: "p"},
        {name: this.$t("Fixed"), code: "f"},
      ];
    },
  },
  props: {
    size: {
      type: Number,
      default: 12,
    },
  },
  data() {
    return {
      selectedMode: null,
      selectedTime: null,
      selectedBreak: null,
      selectedRepeat: null,
      times: [
        {name: "20m", code: "20"},
        {name: "25m", code: "25"},
        {name: "30m", code: "30"},
        {name: "55m", code: "55"},
      ],
      workDuration: 25,
      breakDuration: 5,
      repeat: 4,
      timeLeft: {
        hours: "00",
        minutes: "00",
        seconds: "00"
      },
      breaks: [
        {name: "5m", code: "5"},
        {name: "6m", code: "6"},
        {name: "10m", code: "10"},
      ],
      repeats: [
        {name: "2x", code: "2"},
        {name: "4x", code: "4"},
        {name: "6x", code: "6"},
      ],
      settingsExpanded: false,
    };
  },
  methods: {
    toggle(event) {
      this.$refs.op.toggle(event);
    },
    toggleSettingsExpanded() {
      this.settingsExpanded = !this.settingsExpanded;
    },
  },
  mounted() {
    this.selectedMode = {name: this.$t("Pomodoro"), code: "p"};
  },
};
</script>

<style scoped></style>
