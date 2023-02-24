<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Today") }}</h3>
    </div>
    <div class="text-gray-600 font-medium flex flex-wrap justify-center">
      {{ $t("There are no more scheduled tasks for today") }}.
      {{ $t("Enjoy your spare time") }} 🥳
    </div>
  </div>
</template>

<script>

export default {
  name: "TodayPage",
};
</script>

<style scoped></style>
