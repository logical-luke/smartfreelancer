<template>
  <transition name="fade" mode="out-in">
    <div v-if="isAuthorized">
      <nav class="lg:hidden py-6 px-6 bg-gray-800">
        <div class="flex items-center justify-between">
          <a class="text-2xl text-white font-semibold" href="#">
            <!--            <img class="h-10" src="artemis-assets/logos/artemis-logo.svg" alt="" width="auto">-->
          </a>
          <button
            class="navbar-burger flex items-center rounded focus:outline-none"
          >
            <svg
              class="text-white bg-indigo-500 hover:bg-indigo-600 block h-8 w-8 p-2 rounded"
              viewbox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
            >
              <title>Mobile menu</title>
              <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
            </svg>
          </button>
        </div>
      </nav>
      <div class="hidden lg:block navbar-menu relative z-50">
        <div
          class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-10"
        ></div>
        <nav
          class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pt-6 pb-8 bg-gray-800 overflow-y-auto"
        >
          <div
            class="flex w-full items-center px-6 pb-6 mb-6 lg:border-b border-gray-700"
          >
            <a class="text-xl text-white font-semibold" href="#">
              <!--              <img class="h-8" src="artemis-assets/logos/artemis-logo.svg" alt="" width="auto">-->
            </a>
          </div>
          <div class="px-4 pb-6">
            <ul class="mb-8 text-sm font-medium">
              <SidebarItem go-to="/">
                <template v-slot:title>Dashboard</template>
                <template v-slot:icon>
                  <dashboard-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/clients">
                <template v-slot:title>Clients</template>
                <template v-slot:icon>
                  <users-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/projects">
                <template v-slot:title>Projects</template>
                <template v-slot:icon>
                  <briefcase-icon />
                </template>
              </SidebarItem>
              <SidebarItem go-to="/tasks">
                <template v-slot:title>Tasks</template>
                <template v-slot:icon>
                  <list-icon />
                </template>
              </SidebarItem>
            </ul>
            <div class="pt-8 text-sm font-medium">
              <SidebarLogout />
            </div>
          </div>
        </nav>
      </div>
    </div>
  </transition>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import SidebarItem from "@/components/ui/SidebarItem.vue";
import ListIcon from "vue-tabler-icons/icons/ListIcon";
import BriefcaseIcon from "vue-tabler-icons/icons/BriefcaseIcon";
import DashboardIcon from "vue-tabler-icons/icons/DashboardIcon";
import SidebarLogout from "@/components/ui/SidebarLogout.vue";
import UsersIcon from "vue-tabler-icons/icons/UsersIcon";

export default {
  name: "Sidebar",
  components: {
    UsersIcon,
    SidebarLogout,
    DashboardIcon,
    BriefcaseIcon,
    ListIcon,
    SidebarItem,
  },
  computed: {
    ...mapGetters(["isAuthorized"]),
  },
};
</script>
