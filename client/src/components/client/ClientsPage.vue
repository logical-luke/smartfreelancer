<template>
  <NewButton go-to="/client/create/">Client</NewButton>
  <div class="flex flex-wrap">
    <TransitionGroup name="fade" class="list-element">
      <template v-for="client in clients" :key="client.id">
        <ClientGridItem
          :id="client.id"
          :description="client.description"
          :name="client.name"
        ></ClientGridItem>
      </template>
    </TransitionGroup>
  </div>
</template>

<script>
import { mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ClientGridItem from "@/components/client/ClientGridItem.vue";
import store from "@/store";

export default {
  name: "ClientsPage",
  components: { ClientGridItem, NewButton },
  computed: mapState({
    clients: (state) => state.clients.all
  }),
  mounted() {
    store.commit("client/clearClient");
  }
};
</script>

<style scoped></style>
