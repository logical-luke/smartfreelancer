<template>
  <div class="flex mb-8">
    <h3 class="text-xl font-bold">{{ $t("Deep Work Hub") }}</h3>
  </div>
  <div class="text-gray-600 font-medium flex flex-wrap mt-4">
    {{ $t("There are no more scheduled tasks for today") }}.
    {{ $t("Enjoy your spare time") }} 🥳
  </div>
</template>

<script>
export default {
  name: "DeepWorkHub",
};
</script>
