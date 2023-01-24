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
            class="flex flex-col lg:flex-row lg:space-x-3 mt-4 md:mt-0 w-full md:w-96 content-start"
          >
            <div class="flex items-center space-x-3">
              <TrackedTask />
            </div>
            <div class="flex items-center space-x-3">
              <TrackedProject />
            </div>
            <div class="flex items-center space-x-3">
              <TrackedClient />
            </div>
          </div>
        </div>
      </div>
    </nav>
  </section>
</template>

<script>
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";
import TrackedProject from "@/components/ui/TrackedProject.vue";
import TrackedTask from "@/components/ui/TrackedTask.vue";
import TimerTime from "@/components/ui/TimerTime.vue";
import TrackedClient from "@/components/ui/TrackedClient.vue";

export default {
  name: "Navbar",
  components: {
    TrackedClient,
    TimerTime,
    TrackedTask,
    TrackedProject,
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
