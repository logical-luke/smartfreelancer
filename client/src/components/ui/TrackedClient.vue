<template>
  <users-icon />
  <div class="w-28">
    <v-select
      :options="[]"
      placeholder="Client"
    >
    </v-select>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import store from "@/store";
import UsersIcon from "vue-tabler-icons/icons/UsersIcon";

export default {
  name: "TrackedClient",
  components: { UsersIcon, vSelect },
  data() {
    return {
      value: null,
      options: ["Project", "list", "of", "options"]
    };
  },
  computed: {
    ...mapState({
      timer: (state) => state.timer.current,
      projects: (state) => state.projects.all,
      subject: (state) => state.timer.current.subjectName
    }),
    ...mapGetters({ getProjectsNames: "projects/getProjectsNames" })
  }
};
</script>

<style scoped>

</style>
