<template>
  <section class="py-8">
    <div class="container px-4 mx-auto">
      <h1 class="mb-2 text-2xl font-bold font-heading">Add client</h1>
      <form @submit.prevent="submitForm">
        <client-form />
        <div class="flex flex-wrap space-x-4">
          <div>
            <submit-button>
              Add
              <template #icon>
                <square-plus-icon />
              </template>
            </submit-button>
          </div>
          <div>
            <back-button />
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import ClientForm from "@/components/client/ClientForm.vue";
import { mapState } from "vuex";
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
    this.$store.dispatch("client/clearClient");
  },
  methods: {
    async submitForm() {
      await this.$store.dispatch("clients/createClient", this.client);

      this.$router.push("/clients");
    },
  },
};
</script>

<style scoped></style>
