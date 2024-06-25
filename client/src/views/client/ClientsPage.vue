<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
  </div>
  <div v-if="clients.length > 0" class="flex container flex-wrap gap-8 mb-8">
    <transition-group name="fade" mode="out-in">
      <template v-for="client in clients" :key="client.id">
        <client-item
            :id="client.id"
            :name="client.name"
            :email="client.email"
            :phone="client.phone"
            :avatar="client.avatar"
        />
      </template>
    </transition-group>
  </div>
  <main-action-button @click="goToAddClient" class="w-full md:w-auto">{{ $t("Add Client") }}</main-action-button>
</template>

<script>
import {mapState} from "vuex";
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
      }
    }
  },
  computed: mapState({
    clients: (state) => state.clients.all,
  }),
  methods: {
    async goToAddClient() {
      await router.push({name: "AddClientPage"});
    },
  }
};
</script>
