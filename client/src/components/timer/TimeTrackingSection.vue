<template>
    <div
      class="flex flex-wrap gap-3"
    >
      <div class="flex items-center gap-3">
        <timer-button :size="12" :global="true" />
        <pomodoro-timer :size="12" />
        <duration-input />
      </div>
      <div class="flex items-center w-auto gap-3">
        <select-subject-button />
        <description-input />
      </div>
    </div>
</template>

<style>
.v-select .vs__selected-options {
  flex-wrap: nowrap;
  overflow: hidden;
  width: 40px;
  max-height: 28px;
}
</style>

<script>
import TimerButton from "@/components/timer/TimerButton.vue";
import SelectSubjectButton from "@/components/timer/SelectSubjectButton.vue";
import DurationInput from "@/components/DurationInput.vue";
import PomodoroTimer from "@/components/PomodoroTimer.vue";
import DescriptionInput from "@/components/DescriptionInput.vue";

export default {
  name: "TimeTrackingSection",
  data() {
    return {
      slideFinished: true,
      navHeight: null,
    };
  },
  components: {
    DescriptionInput,
    PomodoroTimer,
    DurationInput,
    SelectSubjectButton,
    TimerButton,
  },
};
</script>
