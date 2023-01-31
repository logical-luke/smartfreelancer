<template>
  <div class="mx-auto lg:ml-80">
    <section class="py-8">
      <div class="container px-4 mx-auto">
        <div class="flex flex-wrap items-center mb-6">
          <h3 class="text-xl font-bold">Timeline</h3>
        </div>
        <transition-group name="fade" class="transition-element">
          <template v-for="timeEntry in timeEntries" :key="timeEntry.id">
            <time-entry :time-entry="timeEntry"></time-entry>
          </template>
        </transition-group>
      </div>
    </section>
  </div>
</template>

<script>
import { mapState } from "vuex";
import TimeEntry from "@/components/timeline/TimeEntry.vue";
import store from "@/store";

export default {
  name: "TimelinePage",
  components: { TimeEntry },
  computed: {
    ...mapState({
      timeEntries: (state) => state.timeEntries.all
    })
  },
  created() {
    store.dispatch("timeEntries/getTimeEntries");
  }
};
</script>

<style scoped></style>
