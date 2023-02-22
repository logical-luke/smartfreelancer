<template>
  <a
    class="flex cursor-pointer items-center pl-3 py-3 pr-2 text-gray-50 hover:bg-gray-900 rounded"
    @click="logout()"
  >
    <span class="inline-block mr-4">
      <logout-icon />
    </span>
    <span>{{ $t("Log out")}}</span>
  </a>
</template>

<script>
import { mapActions } from "vuex";
import LogoutIcon from "vue-tabler-icons/icons/LogoutIcon";

export default {
  name: "SidebarLogout",
  components: { LogoutIcon },
  methods: {
    ...mapActions(["logout"]),
  },
};
</script>
