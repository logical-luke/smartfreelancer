<template>
  <tr :class="greyBackground ? 'bg-gray-50' : ''" class="text-xs">
    <td v-if="bulkMode">
      <div class="flex items-center justify-center">
        <input @click="toggleSelect" :checked="selected" type="checkbox" />
      </div>
    </td>
    <td class="p-2">
      <p class="font-medium text-sm">{{ name }}</p>
    </td>
    <td class="p-2">
      <div class="flex items-center space-x-2">
        <toggle-timer-button :size="8" :client-id="id" />
        <edit-button
          class="mt-1"
          :goTo="`/client/edit/${id}`"
          :icon-only="true"
        />
        <delete-button
          @delete="deleteClient(id)"
          :subject="name"
          :icon-only="true"
        />
      </div>
    </td>
  </tr>
</template>

<script>
import DeleteButton from "@/components/ui/DeleteButton.vue";
import EditButton from "@/components/ui/EditButton.vue";
import ToggleTimerButton from "@/components/ui/ToggleTimerButton.vue";
import { mapActions } from "vuex";

export default {
  name: "ClientListItem",
  components: { ToggleTimerButton, EditButton, DeleteButton },
  props: {
    name: {
      type: String,
      required: true,
    },
    description: {
      type: String,
    },
    id: {
      type: String,
      required: true,
    },
    greyBackground: {
      type: Boolean,
      default: false,
    },
    bulkMode: {
      type: Boolean,
      default: false,
    },
    selected: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    ...mapActions("clients", ["deleteClient"]),
    toggleSelect(event) {
      const action = event.target.checked ? "add" : "remove";
      this.$emit("toggle-select", {
        id: this.id,
        action: action,
      });
    },
  },
  emits: ["toggle-select"],
};
</script>

<style scoped></style>
