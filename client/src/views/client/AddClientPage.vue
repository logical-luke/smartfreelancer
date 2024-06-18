<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Add Client") }}</h3>
    </div>
  </div>
  <div class="flex container">
    <div class="flex flex-col gap-4 w-full">

      <client-form props="client" />

      <div class="flex gap-4 justify-center md:justify-start w-full md:w-1/2">
        <main-action-button @click="submitForm" class="w-1/2 md:w-auto">
          {{ $t("Add Client") }}
        </main-action-button>
        <router-link :to="this.clientsPageRoute">
          <action-button class="w-1/2 md:w-auto">{{ $t("Cancel") }}</action-button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import ClientForm from "@/components/client/ClientForm.vue";
import ActionButton from "@/components/ActionButton.vue";
import MainActionButton from "@/components/MainActionButton.vue";

export default {
  name: "AddClientPage",
  components: {
    MainActionButton,
    ActionButton,
    ClientForm,
  },
  data() {
    return {
      clientsPageRoute: {
        name: "ClientsPage"
      }
    }
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
