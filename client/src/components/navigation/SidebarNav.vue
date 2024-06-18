<template>
  <transition name="fade" mode="in-out">
    <div v-if="isAuthorized" class="relative z-50">
      <nav class="lg:hidden py-6 px-4 bg-gray-800">
        <div class="flex flex-row items-center justify-between mr-2">
          <div class="flex">
            <icon-only-logo />
          </div>
          <div class="flex-row flex items-center justify-end gap-2">
            <synchronization-status />
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
            class="flex flex-row justify-between items-center px-4 pb-8 mr-2 lg:mr-0 mb-8"
          >
            <div class="flex">
              <icon-only-logo />
            </div>
            <div class="flex flex-row items-center justify-end gap-2">
              <synchronization-status />
              <button
                @click="toggle"
                class="lg:hidden navbar-burger p-2 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
              >
                <x-icon />
              </button>
            </div>
          </div>
          <div class="px-4 pb-8">
            <ul class="mb-8 text-sm font-medium">
              <sidebar-item go-to="/">
                {{ $t("Deep Work Hub") }}
                <template #icon>
                  <target-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/calendar">
                {{ $t("Calendar") }}
                <template #icon>
                  <calendar-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/tasks">
                {{ $t("Tasks") }}
                <template #icon>
                  <checklist-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/projects">
                {{ $t("Projects") }}
                <template #icon>
                  <briefcase-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/clients">
                {{ $t("Clients") }}
                <template #icon>
                  <friends-icon />
                </template>
              </sidebar-item>
              <sidebar-item go-to="/reports">
                {{ $t("Reports") }}
                <template #icon>
                  <report-icon />
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
              <sidebar-item go-to="/settings">
                {{ $t("Settings") }}
                <template #icon>
                  <settings-icon />
                </template>
              </sidebar-item>
              <sidebar-logout />
            </ul>
            <language-switcher />
          </div>
        </nav>
      </div>
    </div>
  </transition>
</template>

<script>
import { mapGetters } from "vuex";
import SidebarItem from "@/components/navigation/SidebarItem.vue";
import BriefcaseIcon from "vue-tabler-icons/icons/BriefcaseIcon";
import SidebarLogout from "@/components/navigation/SidebarLogout.vue";
import Menu2Icon from "vue-tabler-icons/icons/Menu2Icon";
import XIcon from "vue-tabler-icons/icons/XIcon";
import ChecklistIcon from "vue-tabler-icons/icons/ChecklistIcon";
import FileDollarIcon from "vue-tabler-icons/icons/FileDollarIcon";
import ShoppingCartIcon from "vue-tabler-icons/icons/ShoppingCartIcon";
import BoltIcon from "vue-tabler-icons/icons/BoltIcon";
import SettingsIcon from "vue-tabler-icons/icons/SettingsIcon";
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import CalendarIcon from "vue-tabler-icons/icons/CalendarIcon";
import IconOnlyLogo from "@/components/IconOnlyLogo.vue";
import ReportIcon from "vue-tabler-icons/icons/ReportIcon";
import SynchronizationStatus from "@/components/SynchronizationStatus.vue";
import FriendsIcon from "vue-tabler-icons/icons/FriendsIcon";
import TargetIcon from "vue-tabler-icons/icons/TargetIcon";

export default {
  name: "SidebarNav",
  components: {
    FriendsIcon,
    SynchronizationStatus,
    ReportIcon,
    IconOnlyLogo,
    CalendarIcon,
    LanguageSwitcher,
    SettingsIcon,
    BoltIcon,
    ShoppingCartIcon,
    FileDollarIcon,
    ChecklistIcon,
    XIcon,
    Menu2Icon,
    SidebarLogout,
    BriefcaseIcon,
    SidebarItem,
    TargetIcon,
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
    ...mapGetters("authorization", ["isAuthorized"]),
  },
  methods: {
    toggle() {
      this.open = !this.open;
    },
  },
};
</script>
