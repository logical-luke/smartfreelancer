<template>
  <div class="p-8 w-full bg-white shadow rounded">
    <div class="flex justify-between items-center mb-8">
      <div class="flex items-center">
        <img
            class="w-20 h-20 p-2 mr-4 rounded-full border border-indigo-50"
            :src="getAvatar()"
            alt="{{ name }}"
        />
        <div>
          <h3 class="font-medium text-lg">{{ name }}</h3>
        </div>
      </div>
    </div>

    <div v-if="hasPhone() || hasEmail()" class="mb-8 p-4 bg-gray-100 rounded">
      <div v-if="hasEmail()" :class="hasPhone() ? 'mb-4' : ''" class="flex items-center gap-4">
        <mail-icon class="text-gray-500"/>
        <span>{{ $t("Email") }}:</span>
      </div>
      <div v-if="hasPhone()" class="flex items-center gap-4">
        <phone-icon class="text-gray-500"/>
        <span>{{ $t("Phone") }}:</span>
      </div>
    </div>

    <div class="flex flex-col md:flex-row mb-8 gap-4">
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/2">
        <div class="flex items-center gap-4 mb-2">
          <coins-icon class="text-yellow-500"/>
          <p class="text-xs font-medium">{{ $t("Revenue") }}</p>
        </div>
        <span>$12,500</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/2">
        <div class="flex items-center gap-4 mb-2">
          <clock-icon class="text-blue-500"/>
          <p class="text-xs font-medium">{{ $t("Time Worked") }}</p>
        </div>
        <span>147 hours</span>
      </div>
    </div>

    <div class="flex flex-col md:flex-row  gap-4 mb-8">
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <progress-icon class="text-amber-500"/>
          <p class="text-xs font-medium">{{ $t("Ongoing Tasks") }}</p>
        </div>
        <span>2</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <calendar-icon class="text-blue-400"/>
          <p class="text-xs font-medium">{{ $t("Planned Tasks") }}</p>
        </div>
        <span>14</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <progress-check-icon class="text-green-500"/>
          <p class="text-xs font-medium">{{ $t("Finished Tasks") }}</p>
        </div>
        <span>3</span>
      </div>
      <div class="p-4 bg-gray-100 rounded w-full md:w-1/4">
        <div class="flex items-center gap-4 mb-2">
          <progress-x-icon class="text-red-500"/>
          <p class="text-xs font-medium">{{ $t("Blocked Tasks") }}</p>
        </div>
        <span>1</span>
      </div>
    </div>

    <div class="flex gap-4 flex-col items-center md:flex-row">
      <action-button>{{ $t("View Details") }}</action-button>
      <action-button>{{ $t("Edit") }}</action-button>
      <action-button>{{ $t("Archive") }}</action-button>
    </div>
  </div>
</template>

<script>
import MailIcon from "vue-tabler-icons/icons/MailIcon";
import PhoneIcon from "vue-tabler-icons/icons/PhoneIcon";
import ActionButton from "@/components/ActionButton.vue";
import CoinsIcon from "vue-tabler-icons/icons/CoinsIcon";
import ClockIcon from "vue-tabler-icons/icons/ClockIcon";
import ProgressIcon from "vue-tabler-icons/icons/ProgressIcon";
import ProgressXIcon from "vue-tabler-icons/icons/ProgressXIcon";
import ProgressCheckIcon from "vue-tabler-icons/icons/ProgressCheckIcon";
import CalendarIcon from "vue-tabler-icons/icons/CalendarIcon";

export default {
  name: "ClientItem",
  components: {
    ProgressCheckIcon,
    ProgressXIcon,
    ProgressIcon,
    ClockIcon,
    CoinsIcon,
    ActionButton,
    PhoneIcon,
    MailIcon,
    CalendarIcon
  },
  methods: {
    hasPhone() {
      return !!this.phone;
    },
    hasEmail() {
      return !!this.email;
    },
    getAvatar() {
      return this.avatar && this.avatar !== '' ? this.avatar : '/client-placeholder.png';
    }
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
  },
};
</script>
