<template>
  <div class="flex flex-wrap items-center gap-4 mb-6">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Clients") }}</h3>
    </div>
    <div>
      <link-button :go-to="this.addClientRoute">{{ $t("Add Client") }}</link-button>
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
import LinkButton from "@/components/LinkButton.vue";

export default {
  name: "ClientsPage",
  components: {
    LinkButton,
    ClientItem,
  },
  data() {
    return {
      addClientRoute: {
        name: "ClientAddPage",
      }
    }
  },
  computed: mapState({
    clients: (state) => state.clients.all,
  }),
};
</script>
