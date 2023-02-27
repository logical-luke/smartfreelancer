<template>
  <button
    type="button"
    @click="toggle"
    :class="sizeClasses"
    class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    <hourglass-empty-icon :size="Math.ceil(size * 1.8)" />
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
        { name: this.$t("Fixed"), code: "f" },
        { name: this.$t("Pomodoro"), code: "p" },
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
        { name: "5m", code: "5" },
        { name: "10m", code: "10" },
        { name: "25m", code: "25" },
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
    this.selectedMode = { name: this.$t("Fixed"), code: "f" };
  },
};
</script>

<style scoped></style>
