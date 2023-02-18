<template>
  <div class="flex min-h-screen -mt-12 flex-col items-center justify-center">
    <transparent-logo-wide size="h-40" />
    <span class="text-xs font-semibold text-indigo-500 uppercase"
      >Error 404</span
    >
    <h2
      class="mt-2 mb-4 text-3xl leading-tight md:text-4xl md:leading-tight lg:text-5xl lg:leading-tight font-bold font-heading"
    >
      Page not found
    </h2>
    <p
      class="mb-8 text-base leading-relaxed lg:text-xl lg:leading-relaxed text-gray-500"
    >
      Sorry! We are unable to find the page you are looking for.
    </p>
    <div>
      <router-link
        to="/"
        class="block md:inline-block px-5 py-3 md:mr-3 mb-3 md:mb-0 text-sm bg-indigo-500 hover:bg-indigo-600 text-white font-semibold border border-indigo-500 hover:border-indigo-600 rounded transition duration-200"
      >
        Return to homepage
      </router-link>
    </div>
  </div>
</template>

<script>
import TransparentLogoWide from "@/components/ui/TransparentLogoWide.vue";

export default {
  name: "NotFoundPage",
  components: { TransparentLogoWide },
};
</script>

<style scoped></style>
