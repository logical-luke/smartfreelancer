<template>
  <div class="mx-auto lg:ml-80">
    <section class="py-8">
      <div class="container px-4 mx-auto">
        <h1 class="mb-2 text-5xl font-bold font-heading">Edit client</h1>
        <form @submit.prevent="submitForm">
          <ClientForm :wait-for-current="true" />
          <div class="flex flex-wrap space-x-4">
            <SubmitButton>
              <template v-slot:title>Save</template>
              <template v-slot:icon><device-floppy-icon /></template>
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
import store from "@/store";
import BackButton from "@/components/ui/BackButton.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import DeviceFloppyIcon from "vue-tabler-icons/icons/DeviceFloppyIcon";
import { useRoute } from "vue-router";

const route = useRoute();

export default {
  name: "ClientEdit",
  components: { DeviceFloppyIcon, SubmitButton, BackButton, ClientForm },
  data() {
    return {
      buttonTitle: "Save",
    };
  },
  computed: mapState({
    client: (state) => state.client.current,
  }),
  async created() {
    const client = await api.getClient(route.params.id);
    this.$store.commit("client/setClient", client);
  },
  methods: {
    async submitForm() {
      await api.updateClient(store.state.client.current);
      this.$store.commit("client/clearClient");
      this.$router.push("/clients");
    },
  },
};
</script>

<style scoped></style>
