<template>
  <section class="py-8">
    <div class="container px-4 mx-auto">
      <h1 class="mb-2 text-2xl font-bold font-heading">Edit client</h1>
      <form @submit.prevent="submitForm">
        <client-form />
        <div class="flex flex-wrap space-x-4">
          <div>
            <submit-button>
              Save
              <template #icon>
                <device-floppy-icon />
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
import DeviceFloppyIcon from "vue-tabler-icons/icons/DeviceFloppyIcon";
import { useRoute } from "vue-router";
import store from "@/store";
import router from "@/router";

export default {
  name: "ClientEditPage",
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
    const client = await store.getters["clients/getClientById"](
      this.route.params.id
    );
    this.$store.commit("client/setClient", client);
  },
  methods: {
    async submitForm() {
      await store.dispatch("clients/updateClient", this.client);
      await router.push("/clients");
    },
  },
  setup() {
    const route = useRoute();

    return { route };
  },
};
</script>

<style scoped></style>
