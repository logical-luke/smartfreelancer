<script lang="ts">
import { defineComponent } from "vue";
import router from "@/router";
import { useAuthorizationStore } from "@/stores/auth";
export default defineComponent({
  name: "SidebarLogout",
  setup() {
    const authStore = useAuthorizationStore();
    const logout = async () => {
      await authStore.logout();
      await router.push("/login");
    };

    return {
      logout,
    };
  },
});
</script>

<template>
  <a
    class="flex cursor-pointer items-center pl-3 py-4 pr-2 text-gray-50 hover:bg-gray-900 rounded"
    @click="logout"
  >
    <span class="inline-block mr-3"></span>
    <span>{{ $t("Log out") }}</span>
  </a>
</template>
