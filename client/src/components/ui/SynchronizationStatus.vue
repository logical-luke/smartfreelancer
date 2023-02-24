<template>
  <div class="p-1" v-if="isOffline">
    <div
      class="flex items-center"
    >
      <button
        type="button"
        v-tooltip.bottom="$t('You are offline')"
        @click="showOfflinePanel"
        class="inline-flex cursor-auto bg-gray-600 items-center justify-center items-center p-2 text-sm font-medium text-white rounded-full transition duration-200"
      >
        <cloud-off-icon size="20" />
      </button>
      <overlay-panel class="lg:hidden" ref="opo">
        {{ $t("You are offline") }}
      </overlay-panel>
    </div>
  </div>
  <div v-else class="p-1">
    <div
      class="flex items-center animate-pulse-slow"
      v-if="!isSynchronized"
    >
      <button
        type="button"
        @click="showSyncPanel"
        v-tooltip.bottom="$t('Synchronization in progress') + '...'"
        class="inline-flex cursor-auto bg-yellow-600 items-center justify-center items-center p-2 text-sm font-medium text-white rounded-full transition duration-200"
      >
        <cloud-download-icon class="animate-pulse-slow" size="20" />
      </button>
      <overlay-panel class="lg:hidden" ref="opp">
        {{ $t('Synchronization in progress') + '...' }}
      </overlay-panel>
    </div>
    <div
      v-else
      class="flex items-center"
    >
      <button
        type="button"
        @click="showSyncedPanel"
        v-tooltip.bottom="getSynchronizationStatus"
        class="inline-flex cursor-auto bg-lime-600 items-center justify-center items-center p-2 text-sm font-medium text-white rounded-full transition duration-200"
      >
        <cloud-icon size="20" />
      </button>
      <overlay-panel class="lg:hidden" ref="ops">
        {{ getSynchronizationStatus }}
      </overlay-panel>
    </div>
  </div>
</template>

<script>
import { MoonLoader } from "vue3-spinner";
import { mapGetters } from "vuex";
import Button from "primevue/button";
import CloudIcon from "vue-tabler-icons/icons/CloudIcon";
import CloudDownloadIcon from "vue-tabler-icons/icons/CloudDownloadIcon";
import CloudOffIcon from "vue-tabler-icons/icons/CloudOffIcon";
import OverlayPanel from "primevue/overlaypanel";

export default {
  name: "SynchronizationStatus",
  components: { OverlayPanel, CloudOffIcon, CloudDownloadIcon, CloudIcon, MoonLoader, Button },
  computed: {
    ...mapGetters("synchronization", ["isSynchronized", "isOffline", "getSynchronizationTime"]),
    getSynchronizationStatus() {
      return this.$t("Last synchronization time") + ": " + this.getSynchronizationTime.toLocaleString();
    }
  },
  methods: {
    showOfflinePanel(event) {
      this.$refs.opo.toggle(event);
    },
    showSyncPanel(event) {
      this.$refs.opp.toggle(event);
    },
    showSyncedPanel(event) {
      this.$refs.ops.toggle(event);
    }
  },
};
</script>

<style scoped>
@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

.animate-pulse-slow {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
