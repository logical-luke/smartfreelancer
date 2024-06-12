<template>
  <div class="flex flex-wrap items-center gap-4 mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
    <div>
      <router-link :to="this.addClientRoute">
        <main-action-button>{{ $t("Add Client") }}</main-action-button>
      </router-link>
    </div>
  </div>
  <div class="flex container flex-wrap gap-8">
    <transition-group name="fade-slower" class="transition-element">
      <template v-for="client in clients" :key="client.id">
        <client-item
            :id="client.id"
            :name="client.name"
            :email="client.email"
            :phone="client.phone"
            :description="client.description"
        />
      </template>
    </transition-group>
  </div>
</template>

<script>
import {mapState} from "vuex";
import ClientItem from "@/components/client/ClientItem.vue";
import MainActionButton from "@/components/MainActionButton.vue";

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
      }
    }
  },
  computed: mapState({
    clients: (state) => state.clients.all,
  }),
};
</script>
