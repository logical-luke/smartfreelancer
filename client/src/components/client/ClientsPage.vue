<template>
  <div class="mx-auto lg:ml-80">
    <NewButton go-to="/client/create/">Client</NewButton>
    <transition name="fade">
      <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0">
        <template v-for="client in clients" :key="client.id">
          <ClientPage
            :id="client.id"
            :description="client.description"
            :name="client.name"
          ></ClientPage>
        </template>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapState } from "vuex";
import NewButton from "@/components/ui/NewButton.vue";
import ClientPage from "@/components/client/ClientPage.vue";
import store from "@/store";

export default {
  name: "ClientsPage",
  components: { ClientPage, NewButton },
  computed: mapState({
    clients: (state) => state.clients.all,
  }),
  mounted() {
    store.commit("client/clearClient");
  },
};
</script>

<style scoped></style>
