<script>
import { mapActions, mapState } from "vuex";
import ClientItem from "@/components/client/ClientItem.vue";
import MainActionButton from "@/components/MainActionButton.vue";
import router from "@/router";

export default {
  name: "ClientsPage",
  components: {
    MainActionButton,
    ClientItem,
  },
  data() {
    return {
      addClientRoute: {
        name: "AddClientPage",
      },
    };
  },
  computed: mapState({
    clients: (state) => state.clients.clients,
  }),
  methods: {
    async goToAddClient() {
      await router.push({ name: "AddClientPage" });
    },
    ...mapActions({
      loadClients: "clients/load",
    }),
  },
  async created() {
    await this.loadClients();
  },
};
</script>

<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
  </div>
  <transition name="slide">
    <div v-if="clients.length > 0" class="flex container flex-wrap gap-8 mb-8">
      <transition-group name="slide">
        <client-item
          v-for="client in clients"
          :id="client.id"
          :key="client.id"
          :name="client.name"
          :email="client.email"
          :phone="client.phone"
          :avatar="client.avatar"
          :revenue="client.revenue"
          :time-worked="client.timeWorked"
          :ongoing-tasks="client.ongoingTasks"
          :planned-tasks="client.plannedTasks"
          :finished-tasks="client.finishedTasks"
          :blocked-tasks="client.blockedTasks"
        />
      </transition-group>
    </div>
  </transition>
  <main-action-button class="w-full md:w-auto" @click="goToAddClient">{{
    $t("Add Client")
  }}</main-action-button>
</template>
