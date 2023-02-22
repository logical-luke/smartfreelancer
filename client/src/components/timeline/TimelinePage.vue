<template>
  <div class="container px-4 mx-auto">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Timeline") }}</h3>
    </div>
    <transition name="fade" mode="out-in">
      <div
        class="flex justify-center h-[12rem] md:h-[32rem] items-center"
        v-if="!entriesLoaded"
      >
        <moon-loader :color="spinnerColor" :loading="!entriesLoaded" />
      </div>
      <div v-else>
        <div class="w-28 mb-3">
          <Datepicker
            v-model="date"
            auto-apply
            :enable-time-picker="false"
            hide-input-icon
            :clearable="false"
            show-now-button
            @update:model-value="updateDate"
          />
        </div>
        <div v-if="timeEntries.length > 0">
          <transition-group name="fade" class="transition-element">
            <time-entry
              v-for="timeEntry in timeEntries"
              :key="timeEntry.id"
              :time-entry="timeEntry"
            />
          </transition-group>
        </div>
        <div class="flex mt-6 text-gray-600 font-medium justify-center" v-else>
          {{ $t("No entries yet") }}
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapState } from "vuex";
import TimeEntry from "@/components/timeline/TimeEntry.vue";
import store from "@/store";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { MoonLoader } from "vue3-spinner";

export default {
  name: "TimelinePage",
  components: { MoonLoader, TimeEntry, Datepicker },
  data() {
    return {
      date: new Date(),
      entriesLoaded: false,
      spinnerColor: "#382CDD",
    };
  },
  methods: {
    async updateDate() {
      this.entriesLoaded = false;
      await store.dispatch("timeEntries/getTimeEntries", {
        date: this.date.toISOString().split("T")[0],
      });
      this.entriesLoaded = true;
    },
  },
  computed: {
    ...mapState({
      timeEntries: (state) => state.timeEntries.all,
    }),
  },
  async created() {
    await store.dispatch("timeEntries/getTimeEntries", {
      date: this.date.toISOString().split("T")[0],
    });
    this.entriesLoaded = true;
  },
};
</script>

<style scoped></style>
