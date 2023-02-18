<template>
  <button
    type="button"
    @click="toggle"
    class="h-12 w-12 inline-flex border-2 border-indigo-500 items-center justify-center items-center px-2 py-2 text-sm font-medium text-white rounded-full transition duration-200"
  >
    🍅
  </button>
  <overlay-panel ref="op" :show-close-icon="true">
    <div class="flex gap-2 flex-col items-center justify-center">
      <select-button
        v-model="selectedTime"
        :options="times"
        optionLabel="name"
      />
      <input-text
        type="text"
        class="w-16"
        v-model="customTime"
        placeholder="30m"
      />
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

export default {
  name: "PomodoroTimer",
  components: { OverlayPanel, SelectButton, InputText },
  data() {
    return {
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
};
</script>

<style scoped></style>
