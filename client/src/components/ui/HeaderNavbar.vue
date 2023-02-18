<template>
  <section class="py-5 px-6 bg-white shadow">
    <nav class="relative">
      <div class="flex items-center">
        <div class="flex flex-wrap md:space-x-3 content-start items-center">
          <div class="flex items-center gap-2">
            <toggle-timer-button size="12" :global="true" />
            <pomodoro-timer />
            <timer-time />
            <start-time />
            <end-time />
            <transition name="fade" mode="in-out">
              <div class="md:hidden px-4" v-if="!isSynchronised">
                <sync-loader
                  title="Synchronising..."
                  :size="spinnerSize"
                  :color="spinnerColor"
                  :loading="!isSynchronised"
                />
              </div>
            </transition>
          </div>
          <div
            class="flex flex-col space-y-2 lg:space-y-0 lg:flex-row lg:space-x-3 mt-4 md:mt-0 w-full md:w-96 content-start"
          >
            <div class="flex items-center lg:space-x-3">
              <tracked-subject />
            </div>
          </div>
          <transition name="fade" mode="in-out">
            <div class="hidden md:flex lg:px-4" v-if="!isSynchronised">
              <sync-loader
                title="Synchronising..."
                :size="spinnerSize"
                :color="spinnerColor"
                :loading="!isSynchronised"
              />
            </div>
          </transition>
        </div>
      </div>
    </nav>
  </section>
</template>

<script>
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";
import TrackedSubject from "@/components/ui/TrackedSubject.vue";
import TimerTime from "@/components/ui/TimerTime.vue";
import { mapGetters } from "vuex";
import { SyncLoader } from "vue3-spinner";
import StartTime from "@/components/ui/StartTime.vue";
import EndTime from "@/components/ui/EndTime.vue";
import PomodoroTimer from "@/components/ui/PomodoroTimer.vue";

export default {
  name: "HeaderNavbar",
  data() {
    return {
      spinnerColor: "#382CDD",
      spinnerSize: "8px",
    };
  },
  computed: {
    ...mapGetters(["isSynchronised"]),
  },
  components: {
    PomodoroTimer,
    EndTime,
    StartTime,
    SyncLoader,
    TimerTime,
    TrackedSubject,
    ToggleTimerButton,
  },
};
</script>

<style>
.v-select .vs__selected-options {
  flex-wrap: nowrap;
  overflow: hidden;
  width: 40px;
  max-height: 28px;
}
</style>
