<template>
  <v-select
    :options="options"
    placeholder="Select client"
    v-model="clientId"
    @update:modelValue="setClient"
  >
  </v-select>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { onMounted, ref } from "vue";
import store from "@/store";
import clientService from "@/services/client";

export default {
  name: "SelectClient",
  components: { vSelect },
  props: {
    selected: Number,
  },
  watch: {
    clients() {
      this.options = clientService.getTimerOptions();
    },
  },
  methods: {
    setClient(client) {
      if (client) {
        return this.$emit("updated", client.id);
      }

      return this.$emit("updated", null);
    },
  },
  setup(props) {
    let clientId = ref("clientId");
    let options = ref("options");
    clientId.value = null;
    options.value = clientService.getTimerOptions();
    onMounted(() => {
      if (props.selected) {
        const selectedClient = options.value
          .filter((client) => client.id === props.selected)
          .pop();
        clientId.value = {
          id: selectedClient.id,
          label: selectedClient.label,
        };
      }
    });
    return {
      clientId,
      options,
    };
  },
  emits: ["updated"],
  created() {
    this.options = clientService.getTimerOptions();
  },
};
</script>

<style scoped></style>
