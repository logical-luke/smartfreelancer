<script>
import ActionButton from "@/components/ActionButton.vue";
import router from "@/router";
import { mapActions } from "vuex";

export default {
  name: "ClientItem",
  components: {
    ActionButton,
  },
  methods: {
    hasPhone() {
      return !!this.phone;
    },
    hasEmail() {
      return !!this.email;
    },
    getAvatar() {
      return this.avatar && this.avatar !== ""
        ? this.avatar
        : "/client-placeholder.png";
    },
    async goToEditClientPage() {
      await router.push({ name: "EditClientPage", params: { id: this.id } });
    },
    delete() {
      try {
        this.deleteClient(this.id);
      } catch (e) {
        console.error(e);
      }
    },
    ...mapActions({
      deleteClient: "clients/delete",
    }),
  },
  props: {
    id: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    email: {
      type: String,
    },
    phone: {
      type: String,
    },
    avatar: {
      type: String,
    },
    revenue: {
      type: Number,
    },
    timeWorked: {
      type: Number,
    },
    ongoingTasks: {
      type: Number,
    },
    plannedTasks: {
      type: Number,
    },
    finishedTasks: {
      type: Number,
    },
    blockedTasks: {
      type: Number,
    },
  },
};
</script>

<template>
  <div class="p-8 w-full bg-white shadow rounded">
    <div class="flex justify-between items-center mb-8">
      <div class="flex items-center">
        <img
          class="w-20 h-20 p-1 mr-4 rounded-full border border-indigo-700"
          :src="getAvatar()"
          :alt="name"
        />
        <div>
          <h3 class="font-medium text-lg">{{ name }}</h3>
        </div>
      </div>
    </div>

    <div v-if="hasPhone() || hasEmail()" class="mb-8 p-4 bg-gray-100 rounded">
      <div
        v-if="hasEmail()"
        :class="hasPhone() ? 'mb-4' : ''"
        class="flex items-center gap-4"
      >
        <span>{{ $t("Email") }}: {{ email }}</span>
      </div>
      <div v-if="hasPhone()" class="flex items-center gap-4">
        <span>{{ $t("Phone") }}: {{ phone }}</span>
      </div>
    </div>

    <div class="flex flex-col md:flex-row mb-8 gap-4">
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/2">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ $t("Revenue") }}</p>
        </div>
        <span>{{ revenue }} $</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/2">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ $t("Time Worked") }}</p>
        </div>
        <span>{{ timeWorked }} {{ $t("hours") }}</span>
      </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4 mb-8">
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ $t("Ongoing Tasks") }}</p>
        </div>
        <span>{{ ongoingTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ $t("Planned Tasks") }}</p>
        </div>
        <span>{{ plannedTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ $t("Finished Tasks") }}</p>
        </div>
        <span>{{ finishedTasks }}</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <p class="text-xs font-medium">{{ $t("Blocked Tasks") }}</p>
        </div>
        <span>{{ blockedTasks }}</span>
      </div>
    </div>

    <div class="flex gap-4 flex-col items-center md:flex-row">
      <ActionButton @click="goToEditClientPage">{{
        $t("Edit")
      }}</ActionButton>
      <ActionButton @click="this.delete">{{ $t("Delete") }}</ActionButton>
    </div>
  </div>
</template>
