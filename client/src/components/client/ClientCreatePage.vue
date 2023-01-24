<template>
  <div class="mx-auto lg:ml-80">
    <section class="py-8">
      <div class="container px-4 mx-auto">
        <h1 class="mb-2 text-5xl font-bold font-heading">Add client</h1>
        <form @submit.prevent="submitForm">
          <ClientForm />
          <div class="flex flex-wrap space-x-4">
            <SubmitButton>
              <template v-slot:title>Add</template>
              <template v-slot:icon><square-plus-icon /></template>
            </SubmitButton>
            <BackButton />
          </div>
        </form>
      </div>
    </section>
  </div>
</template>

<script>
import ClientForm from "@/components/client/ClientForm.vue";
import { mapState } from "vuex";
import api from "@/api/api";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import SquarePlusIcon from "vue-tabler-icons/icons/SquarePlusIcon";

export default {
  name: "ClientCreate",
  components: { SquarePlusIcon, SubmitButton, BackButton, ClientForm },
  computed: mapState({
    client: (state) => state.client.current,
    userId: (state) => state.user.id,
  }),
  created() {
    this.$store.commit("client/setClient", {
      name: null,
      description: null,
    });
  },
  methods: {
    async submitForm() {
      await api.createClient(this.client);

      this.$router.push("/clients");
    },
  },
};
</script>

<style scoped></style>
