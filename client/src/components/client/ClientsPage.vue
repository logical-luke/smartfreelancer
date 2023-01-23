<template>
  <div class="mx-auto lg:ml-80">
    <NewButton go-to="/client/create/">Client</NewButton>
    <transition name="fade">
      <div>
        <template v-for="client in clients" :key="client.id">
          <Client :id="client.id" :description="client.description" :name="client.name"></Client>
        </template>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import Client from "@/components/client/Client.vue";

let clients = {};

export default {
  name: "Clients",
  components: { Client, NewButton },
  computed: mapState({
    clients: (state) => state.clients.all
  }),
  mounted() {
    this.$store.commit("client/clearClient");
  }
};
</script>

<style scoped></style>
