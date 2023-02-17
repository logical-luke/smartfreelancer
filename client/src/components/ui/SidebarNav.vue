<template>
  <transition name="fade" mode="out-in">
    <div v-if="isAuthorized">
      <nav class="lg:hidden py-6 px-6 bg-gray-800">
        <div class="flex items-center justify-between">
          <TransparentLogoWide />
          <button
            @click="toggle"
            class="navbar-burger p-1 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
          >
            <Menu2Icon />
          </button>
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
            class="flex justify-between px-6 pb-6 mb-6 lg:border-b border-gray-700"
          >
            <div>
              <TransparentLogoWide />
            </div>
            <div class="flex content-center items-center">
              <button
                @click="toggle"
                class="lg:hidden navbar-burger p-1 bg-indigo-500 text-white flex items-center rounded focus:outline-none"
              >
                <XIcon />
              </button>
            </div>
          </div>
          <div class="px-4 pb-6">
            <ul class="mb-8 text-sm font-medium">
              <SidebarItem go-to="/">
                <template #title>Today</template>
                <template #icon>
                  <sun-high-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/timeline">
                <template #title>Timeline</template>
                <template #icon>
                  <clock-hour4-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/clients">
                <template #title>Clients</template>
                <template #icon>
                  <users-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/projects">
                <template #title>Projects</template>
                <template #icon>
                  <briefcase-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/tasks">
                <template #title>Tasks</template>
                <template #icon>
                  <checklist-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/reports">
                <template #title>Reports</template>
                <template #icon>
                  <chart-bar-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/invoices">
                <template #title>Invoices</template>
                <template #icon>
                  <file-dollar-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/automations">
                <template #title>Automations</template>
                <template #icon>
                  <bolt-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/expenses">
                <template #title>Expenses</template>
                <template #icon>
                  <shopping-cart-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/settings">
                <template #title>Settings</template>
                <template #icon>
                  <settings-icon />
                </template>
              </SidebarItem>
              <SidebarLogout />
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

export default {
  name: "SidebarNav",
  components: {
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
