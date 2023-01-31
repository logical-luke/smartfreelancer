<template>
  <section class="py-5 px-6 bg-white shadow">
    <nav class="relative">
      <div class="flex items-center">
        <div class="flex flex-wrap space-x-3 content-start items-center mr-4">
          <div class="flex items-center space-x-3">
            <ToggleTimerButton :global="true" />
            <TimerTime />
          </div>
          <div
            class="flex flex-col space-y-2 lg:space-y-0 lg:flex-row lg:space-x-3 mt-4 md:mt-0 w-full md:w-96 content-start"
          >
            <div class="flex items-center space-x-3">
              <TrackedSubject />
            </div>
          </div>
          <div class="px-4">
            <SyncLoader
              :size="spinnerSize"
              :color="spinnerColor"
              :loading="!isSynchronised"
            />
          </div>
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

export default {
  name: "HeaderNavbar",
  data() {
    return {
      spinnerColor: "#382CDD",
      spinnerSize: "8px",
    };
  },
  computed: {
    ...mapGetters(["isSynchronised"])
  },
  components: {
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
