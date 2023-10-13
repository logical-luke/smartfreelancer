<template>
  <button
      type="button"
      @click="toggle"
      :class="sizeClasses"
      class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    <svg
        version="1.1"
        id="tomato"
        viewBox="0 0 120 125"
        xml:space="preserve"
        width="23"
        height="23"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:svg="http://www.w3.org/2000/svg">
      <defs id="defs4"/>
      <svg:style
          type="text/css"
          id="style1">.st0{fill-rule:evenodd;clip-rule:evenodd;fill:#75AA00;} .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#F23329;} .st2{fill-rule:evenodd;clip-rule:evenodd;fill:#D61D1D;stroke:#F60D11;stroke-width:0.216;stroke-miterlimit:2.6131;} .st3{fill-rule:evenodd;clip-rule:evenodd;fill:#D61D1D;}</svg:style>
      <g id="g4">
        <path
            class="st0"
            d="m 61.11,1.77 c 2.01,8.85 3.96,17.04 5.62,21.54 4.95,-0.24 8.16,-1.58 11.52,-2.81 4.13,-1.52 7.6,-3.47 10.62,-5.74 2.3,-1.51 3.6,-0.82 3.41,3.08 -0.7,3.63 -2.57,6.4 -5.41,8.49 0.15,-0.01 0.3,-0.01 0.45,-0.02 -0.55,0.05 -1.1,0.1 -1.66,0.16 v 0 c 7.15,2.1 12.36,6.45 14.18,14.68 0.13,4.15 -0.98,6.06 -2.72,3.68 -9.16,-7.27 -17.26,-11.14 -25.06,-9.17 2.63,6.27 8.12,10.6 13.8,15.26 C 75.64,51.07 66.3,47.09 58.04,39.2 48.81,43.73 35.37,42.49 30.3,49.55 27.34,53.18 26.77,51.34 27.56,46.39 29.72,38.5 33.4,33.4 38.78,31.4 30.87,31.7 25.25,29.88 21.79,27.9 25.82,26.39 30.76,26.22 36.35,25.61 L 28.1,22.26 c -1.46,-0.95 -0.82,-1.52 1.55,-1.76 10.6,-0.4 19.89,0.09 26.87,2.2 C 56.41,16.79 53.91,10.88 51.01,4.97 L 58.57,0 61.11,1.77 Z"
            id="path1"/>
        <path
            class="st1"
            d="m 84.77,26.27 c 6.89,2.03 11.92,6.22 13.67,14.16 0.13,4 -0.43,5.59 -2.11,3.29 -8.83,-7.01 -17.16,-10.48 -24.69,-8.58 2.53,6.05 7.57,10.78 13.05,15.28 -9.86,0.14 -18.6,-4.26 -26.57,-11.87 -8.9,4.37 -21.86,3.17 -26.76,9.99 -2.86,3.5 -3.41,1.73 -2.64,-3.05 2.08,-7.61 5.63,-12.53 10.82,-14.46 -8.52,0.32 -14.15,-0.84 -17.36,-3.24 -4.36,1.46 -7.73,3.41 -10.07,5.88 v 0 c -0.05,0.05 -0.11,0.11 -0.17,0.16 -3.28,2.86 -5.83,6.14 -7.71,9.75 -1.97,3.79 -3.19,7.93 -3.73,12.29 -1.4,11.22 1.07,23.51 6.53,34.16 5.21,10.15 13.1,18.78 22.93,23.48 6.11,2.93 12.98,4.84 20.69,5.54 7.78,0.7 16.41,0.17 26.01,-1.78 7.13,-1.46 13.43,-3.71 18.94,-6.64 5.75,-3.06 10.61,-6.84 14.63,-11.17 5.85,-6.31 9.87,-13.97 11.67,-22.67 1.74,-8.39 1.42,-17.77 -1.28,-27.9 v 0 c -0.69,-2.32 -1.51,-4.45 -2.43,-6.42 -0.93,-1.95 -1.95,-3.71 -3.08,-5.28 -3.57,-4.96 -8.23,-8.17 -13.83,-9.85 C 96.47,25.91 90.93,25.6 84.77,26.27 Z"
            id="path2"/>
        <path
            class="st2"
            d="m 37.22,32.14 c 0.73,-0.45 1.51,-0.82 2.32,-1.13 -0.76,0.03 -1.5,0.05 -2.22,0.05 -0.05,0.39 -0.08,0.75 -0.1,1.08 z M 10.1,35.53 C 7.68,37.96 5.73,40.66 4.22,43.57 2.25,47.36 1.03,51.5 0.49,55.86 -0.9,67.09 1.57,79.38 7.03,90.03 c 5.21,10.15 13.1,18.78 22.93,23.48 6.11,2.93 12.98,4.84 20.69,5.54 7.78,0.7 16.41,0.17 26.01,-1.78 7.13,-1.46 13.43,-3.71 18.94,-6.64 5.75,-3.06 10.61,-6.84 14.63,-11.17 4.67,-5.04 8.18,-10.94 10.32,-17.55 -25.87,48.2 -128.8,32.6 -110.45,-46.38 z"
            id="path3"/>
        <path
            class="st3"
            d="m 111.52,80.73 c -0.21,1.13 -1.31,1.88 -2.45,1.66 -1.13,-0.21 -1.88,-1.31 -1.66,-2.45 1.22,-6.49 1.37,-12.12 0.66,-17.05 -0.71,-4.87 -2.27,-9.06 -4.49,-12.75 -0.6,-0.99 -0.28,-2.27 0.71,-2.87 0.99,-0.6 2.27,-0.28 2.87,0.71 2.5,4.15 4.25,8.86 5.05,14.3 0.79,5.4 0.63,11.49 -0.69,18.45 z"
            id="path4"/>
      </g>
    </svg>
  </button>

  <overlay-panel ref="op" :show-close-icon="false">
    <div class="flex gap-2 flex-col items-center justify-center">
      <select-button
          v-model="selectedMode"
          :options="modes"
          optionLabel="name"
      />
      <select-button
          v-model="selectedTime"
          :options="times"
          optionLabel="name"
      />
      <div>
        {{ $t("Custom") }}:
        <input-text
            type="text"
            class="w-16"
            v-model="customTime"
            placeholder="30m"
        />
      </div>
      <button
          type="button"
          class="inline-flex text-center items-center w-auto px-3 flex-nowrap py-3 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
      >
        {{ $t("Start timer") }}
      </button>
    </div>
  </overlay-panel>
</template>

<script>
import OverlayPanel from "primevue/overlaypanel";
import SelectButton from "primevue/selectbutton";
import InputText from "primevue/inputtext";
import HourglassEmptyIcon from "vue-tabler-icons/icons/HourglassEmptyIcon";

export default {
  name: "PomodoroTimer",
  components: {
    HourglassEmptyIcon,
    OverlayPanel,
    SelectButton,
    InputText,
  },
  computed: {
    sizeClasses() {
      return `w-${this.size} h-${this.size}`;
    },
    modes() {
      return [
        {name: this.$t("Fixed"), code: "f"},
        {name: this.$t("Pomodoro"), code: "p"},
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
      times: [
        {name: "5m", code: "5"},
        {name: "10m", code: "10"},
        {name: "25m", code: "25"},
      ],
      customTime: null,
    };
  },
  methods: {
    toggle(event) {
      this.$refs.op.toggle(event);
    },
  },
  mounted() {
    this.selectedMode = {name: this.$t("Fixed"), code: "f"};
  },
};
</script>

<style scoped></style>
