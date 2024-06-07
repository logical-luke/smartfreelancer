<template>
  <div class="container px-4 w-full">
    <div class="flex flex-wrap items-center mb-6">
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
    <div class="flex w-full flex-wrap gap-8">
      <transition-group name="fade-slower" class="transition-element">
        <template v-for="client in clients" :key="client.id">
          <client-item
              :id="client.id"
              :name="client.name"
              :email="client.email"
              :description="client.description"
          />
        </template>
      </transition-group>
    </div>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import ClientItem from "@/components/client/ClientItem.vue";

export default {
  name: "ClientsPage",
  components: {
    ClientItem,
  },
  computed: mapState({
    clients: (state) => state.clients.all,
  }),
  methods: {
    ...mapActions("clients", ["deleteClient", "deleteClients"]),
  }
};
</script>

