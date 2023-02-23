<template>
  <transition name="fade" mode="in-out">
    <div v-if="isAuthorized" class="relative z-50">
      <nav class="lg:hidden py-6 px-6 bg-gray-800">
        <div class="flex flex-row items-center justify-between">
          <div class="flex mr-auto">
            <transparent-logo-wide />
          </div>
          <div class="flex-row flex items-center justify-end gap-2">
            <language-switcher />
            <button
              @click="toggle"
              class="navbar-burger p-2 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
            >
              <menu2-icon />
            </button>
          </div>
        </div>
      </nav>
      <div
        :class="{ hidden: !open }"
        class="lg:block navbar-menu relative z-50"
      >
        <div
          class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-10"
        ></div>
        <nav
          class="flex fixed top-0 left-0 bottom-0 flex-col w-full lg:max-w-xs lg:w-3/4 pt-6 pb-8 bg-gray-800 overflow-y-auto"
        >
          <div
            class="flex flex-row justify-between items-center px-6 pb-6 mb-6 lg:border-b border-gray-700"
          >
            <div class="flex mr-auto">
              <transparent-logo-wide />
            </div>
            <div class="flex flex-row items-center justify-end gap-2">
              <language-switcher />
              <button
                @click="toggle"
                class="lg:hidden navbar-burger p-2 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
              >
                <x-icon />
              </button>
            </div>
          </div>
          <div class="px-4 pb-6">
            <ul class="mb-8 text-sm font-medium">
              <sidebar-item go-to="/">
                {{ $t("Today") }}
                <template #icon>
                  <sun-high-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/timeline">
                {{ $t("Timeline") }}
                <template #icon>
                  <clock-hour4-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/clients">
                {{ $t("Clients") }}
                <template #icon>
                  <users-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/projects">
                {{ $t("Projects") }}
                <template #icon>
                  <briefcase-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/tasks">
                {{ $t("Tasks") }}
                <template #icon>
                  <checklist-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/calendar">
                {{ $t("Calendar") }}
                <template #icon>
                  <calendar-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/metrics">
                {{ $t("Metrics") }}
                <template #icon>
                  <chart-bar-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/invoices">
                {{ $t("Invoices") }}
                <template #icon>
                  <file-dollar-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/expenses">
                {{ $t("Expenses") }}
                <template #icon>
                  <shopping-cart-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/automations">
                {{ $t("Automations") }}
                <template #icon>
                  <bolt-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/forecasts">
                {{ $t("Forecasts") }}
                <template #icon>
                  <robot-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/settings">
                {{ $t("Settings") }}
                <template #icon>
                  <settings-icon />
                </template>
              </sidebar-item>
              <sidebar-logout />
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </transition>
</template>

<script>
import { mapGetters } from "vuex";
import SidebarItem from "@/components/ui/SidebarItem.vue";
import BriefcaseIcon from "vue-tabler-icons/icons/BriefcaseIcon";
import SidebarLogout from "@/components/ui/SidebarLogout.vue";
import UsersIcon from "vue-tabler-icons/icons/UsersIcon";
import Menu2Icon from "vue-tabler-icons/icons/Menu2Icon";
import XIcon from "vue-tabler-icons/icons/XIcon";
import TransparentLogoWide from "@/components/ui/TransparentLogoWide.vue";
import SunHighIcon from "vue-tabler-icons/icons/SunHighIcon";
import ClockHour4Icon from "vue-tabler-icons/icons/ClockHour4Icon";
import ChecklistIcon from "vue-tabler-icons/icons/ChecklistIcon";
import FileDollarIcon from "vue-tabler-icons/icons/FileDollarIcon";
import ChartBarIcon from "vue-tabler-icons/icons/ChartBarIcon";
import ShoppingCartIcon from "vue-tabler-icons/icons/ShoppingCartIcon";
import BoltIcon from "vue-tabler-icons/icons/BoltIcon";
import SettingsIcon from "vue-tabler-icons/icons/SettingsIcon";
import LanguageSwitcher from "@/components/ui/LanguageSwitcher.vue";
import CalendarIcon from "vue-tabler-icons/icons/CalendarIcon";
import RobotIcon from "vue-tabler-icons/icons/RobotIcon";

export default {
  name: "SidebarNav",
  components: {
    RobotIcon,
    CalendarIcon,
    LanguageSwitcher,
    SettingsIcon,
    BoltIcon,
    ShoppingCartIcon,
    ChartBarIcon,
    FileDollarIcon,
    ChecklistIcon,
    ClockHour4Icon,
    SunHighIcon,
    TransparentLogoWide,
    XIcon,
    Menu2Icon,
    UsersIcon,
    SidebarLogout,
    BriefcaseIcon,
    SidebarItem,
  },
  data() {
    return {
      open: false,
    };
  },
  watch: {
    $route() {
      this.open = false;
    },
  },
  computed: {
    ...mapGetters(["isAuthorized"]),
  },
  methods: {
    toggle() {
      this.open = !this.open;
    },
  },
};
</script>
