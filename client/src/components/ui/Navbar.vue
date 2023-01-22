<template>
  <section class="py-5 px-6 bg-white shadow">
    <nav class="relative">
      <div class="flex items-center">
        <div class="flex space-x-3 content-start items-center mr-4">
          <ToggleTimerButton :global="true" />
          <TimerTime />
          <TrackedProject class="font-semibold" />
        </div>
      </div>
    </nav>
  </section>
</template>

<script>
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";
import TrackedProject from "@/components/ui/TrackedSubject.vue";
import TimerTime from "@/components/ui/TimerTime.vue";

export default {
  name: "Navbar",
  components: { TimerTime, TrackedProject, ToggleTimerButton }
};
</script>

<style scoped>

</style>
