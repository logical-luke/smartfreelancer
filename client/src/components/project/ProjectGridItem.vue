<template>
  <tr :class="greyBackground ? 'bg-gray-50' : ''" class="text-xs">
    <td class="flex px-4 py-3">
      <div class="flex items-center">
                    <span
                      class="inline-flex justify-center text-white items-center w-10 h-10 mr-4 bg-indigo-500 rounded">
                      <briefcase-icon />
                    </span>
        <p class="font-medium">{{ name }}</p>
      </div>
    </td>
    <td>
      <div class="flex items-center space-x-2">
        <toggle-timer-button :size="8" :project-id="id" />
        <edit-button class="mt-1" :goTo="`/project/edit/${id}`" :icon-only="true" />
        <delete-button @confirm="deleteProject(id)" :subject="name" :icon-only="true" />
      </div>
    </td>
  </tr>
</template>

<script>
import DeleteButton from "@/components/ui/DeleteButton.vue";
import EditButton from "@/components/ui/EditButton.vue";
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";
import { mapActions } from "vuex";
import BriefcaseIcon from "vue-tabler-icons/icons/BriefcaseIcon";

export default {
  name: "ProjectGridItem",
  components: { BriefcaseIcon, ToggleTimerButton, EditButton, DeleteButton },
  props: {
    name: {
      type: String,
      required: true
    },
    description: {
      type: String
    },
    id: {
      type: String,
      required: true
    },
    greyBackground: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    ...mapActions("projects", ["deleteProject"])
  }
};
</script>

<style scoped></style>
