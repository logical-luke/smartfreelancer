<template>
  <div class="pl-4 pr-6 py-4 mb-2 bg-white shadow rounded transition-element">
    <div class="flex flex-wrap -mx-4 justify-between">
      <div class="mb-4 md:mb-0 px-4 flex">
        <h4 v-if="subject" class="font-medium">{{ subject }}</h4>
        <h4 v-else class="font-medium">{{ $t("Unassigned")}}</h4>
      </div>
      <div class="px-4 flex text-xs text-gray-500 items-center">
        <span class="mr-1">
          <clock-hour4-icon></clock-hour4-icon>
        </span>
        <p>{{ duration }}</p>
      </div>
      <!--      <div class="w-auto mr-16 px-4">-->
      <!--      </div>-->
      <!--      <div class="w-auto px-4">-->
      <!--      </div>-->
    </div>
  </div>
</template>

<script>
import store from "@/store";
import getRelativeTime from "@/services/time/relativeTimeGetter";
import ClockHour4Icon from "vue-tabler-icons/icons/ClockHour4Icon";
import TrackedSubject from "@/components/ui/TrackedSubject.vue";

export default {
  name: "TimeEntry",
  components: { TrackedSubject, ClockHour4Icon },
  computed: {
    subject() {
      const client = store.getters["clients/getClientById"](
        this.timeEntry.clientId
      );
      if (client) {
        return "👤 " + client.name;
      }
      const project = store.getters["projects/getProjectById"](
        this.timeEntry.projectId
      );
      if (project) {
        return "💼 " + project.name;
      }
      const task = store.getters["tasks/getTaskById"](this.timeEntry.taskId);
      if (task) {
        return "📋 " + task.name;
      }

      return "";
    },
    duration() {
      const { hours, minutes, seconds } = getRelativeTime(
        this.timeEntry.startTime,
        this.timeEntry.endTime
      );

      return `${hours}:${minutes}:${seconds}`;
    },
  },
  props: {
    timeEntry: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style scoped></style>
