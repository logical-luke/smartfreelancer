<template>
  <div class="flex h-screen -mt-12 flex-col items-center justify-center">
    <transparent-logo-wide size="h-60" />
    <span class="text-xs font-semibold text-indigo-500 uppercase"
      >{{ $t("Error") }} 404</span
    >
    <h2
      class="mt-2 mb-4 text-3xl leading-tight md:text-4xl md:leading-tight lg:text-5xl lg:leading-tight font-bold font-heading"
    >
      {{ $t("Page not found") }}
    </h2>
    <div>
      <router-link
        to="/"
        class="block md:inline-block px-5 py-3 md:mr-3 mb-3 md:mb-0 text-sm bg-indigo-500 hover:bg-indigo-600 text-white font-semibold border border-indigo-500 hover:border-indigo-600 rounded transition duration-200"
      >
        {{ $t("Return to homepage") }}
      </router-link>
    </div>
  </div>
</template>

<script>
import TransparentLogoWide from "@/components/logo/TransparentLogoWide.vue";

export default {
  name: "NotFoundPage",
  components: { TransparentLogoWide },
};
</script>

<style scoped></style>
