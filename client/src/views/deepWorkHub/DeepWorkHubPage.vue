<template>
  <div class="flex flex-wrap items-center mb-8">
    <h3 class="text-xl font-bold">{{ $t("Deep Work Hub") }}</h3>
  </div>
  <time-tracking-section />
  <div class="text-gray-600 font-medium flex flex-wrap mt-4">
    {{ $t("There are no more scheduled tasks for today") }}.
    {{ $t("Enjoy your spare time") }} 🥳
  </div>
</template>

<script>
import TimeTrackingSection from "@/components/timer/TimeTrackingSection.vue";

export default {
  name: "DeepWorkHub",
  components: {TimeTrackingSection},
};
</script>

