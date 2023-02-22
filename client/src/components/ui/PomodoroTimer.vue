<template>
  <button
    type="button"
    @click="toggle"
    :class="sizeClasses"
    class="inline-flex bg-indigo-500 hover:bg-indigo-600 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    <hourglass-icon :size="Math.ceil(size * 1.8)" />
  </button>
  <overlay-panel ref="op" :show-close-icon="true">
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
        Custom:
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
        Start timer
      </button>
    </div>
  </overlay-panel>
</template>

<script>
import OverlayPanel from "primevue/overlaypanel";
import SelectButton from "primevue/selectbutton";
import InputText from "primevue/inputtext";
import HourglassIcon from "vue-tabler-icons/icons/HourglassIcon";

export default {
  name: "PomodoroTimer",
  components: { HourglassIcon, OverlayPanel, SelectButton, InputText },
  computed: {
    sizeClasses() {
      return `w-${this.size} h-${this.size}`;
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
      selectedTime: null,
      times: [
        { name: "5m", code: "5" },
        { name: "10m", code: "10" },
        { name: "25m", code: "25" },
      ],
      modes: [
        { name: "Fixed", code: "f" },
        { name: "Pomodoro", code: "p" },
      ],
      selectedMode: { name: "Fixed", code: "f" },
      customTime: null,
    };
  },
  methods: {
    toggle(event) {
      this.$refs.op.toggle(event);
    },
  },
};
</script>

<style scoped></style>
