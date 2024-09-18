<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import { onMounted, ref } from "vue";
import store from "@/store";
import {mapState} from "vuex";

export default {
  name: "SelectClient",
  components: { vSelect },
  props: {
    selected: Number,
  },
  computed: {
    ...mapState({
      clients: (state) => state.clients.clients,
    }),
  },
  watch: {
    clients() {
      this.options = this.getClientsOptions();
    },
  },
  methods: {
    setClient(client) {
      if (client) {
        return this.$emit("updated", client.id);
      }

      return this.$emit("updated", null);
    },
    getClientsOptions() {
      return this.clients.all.map((client) => {
        return {
          id: client.id,
          label: client.name,
        };
      })
    }
  },
  setup(props) {
    let clientId = ref("clientId");
    let options = ref("options");
    clientId.value = null;
    options.value = this.getClientsOptions();
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
    this.options = this.getClientsOptions();
  },
};
</script>

<template>
  <v-select
      :options="options"
      placeholder="Select client"
      v-model="clientId"
      @update:modelValue="setClient"
  >
  </v-select>
</template>

<style scoped></style>

