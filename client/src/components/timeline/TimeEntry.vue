<template>
  <div class="pl-4 pr-6 py-4 mb-2 bg-white shadow rounded transition-element">
    <div class="flex flex-wrap items-center -mx-4">
      <div class="w-full md:w-3/6 mb-4 md:mb-0 px-4 flex items-center">
        <h4 class="font-medium">{{ subject }}</h4>
      </div>
      <div
        class="w-auto ml-auto mr-16 px-4 flex items-center text-xs text-gray-500"
      >
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
import { getRelativeTime } from "@/services/relativeTimeGetter";
import ClockHour4Icon from "vue-tabler-icons/icons/ClockHour4Icon";

export default {
  name: "TimeEntry",
  components: { ClockHour4Icon },
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
        new Date(this.timeEntry.startTime * 1000),
        new Date(this.timeEntry.endTime * 1000)
      );

      return `${hours}:${minutes}:${seconds}`;
    }
  },
  props: {
    timeEntry: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped></style>
