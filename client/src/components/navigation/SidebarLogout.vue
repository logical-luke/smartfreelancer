<script>
import authorization from "@/services/authorization";
import router from "@/router";

export default {
  name: "SidebarLogout",
  methods: {
    async logout() {
      await authorization.logout();

      return await router.push("/login");
    },
  },
};
</script>

<template>
  <a
      class="flex cursor-pointer items-center pl-3 py-4 pr-2 text-gray-50 hover:bg-gray-900 rounded"
      @click="logout()"
  >
    <span class="inline-block mr-3">
    </span>
    <span>{{ $t("Log out") }}</span>
  </a>
</template>
