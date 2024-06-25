<template>
  <h1 class="mb-2 text-2xl font-bold font-heading">Edit client</h1>
  <form @submit.prevent="submitForm">
    <client-form/>
    <div class="flex flex-wrap space-x-4">
      <div>
        <submit-button>
          Save
          <template #icon>
            <device-floppy-icon/>
          </template>
        </submit-button>
      </div>
      <div>
      </div>
    </div>
  </form>
</template>

<script>
import ClientForm from "@/components/client/AddClientForm.vue";
import {mapState} from "vuex";
import SubmitButton from "@/components/SubmitButton.vue";
import DeviceFloppyIcon from "vue-tabler-icons/icons/DeviceFloppyIcon";
import {useRoute} from "vue-router";
import store from "@/store";
import router from "@/router";

export default {
  name: "ClientEditPage",
  components: {DeviceFloppyIcon, SubmitButton, ClientForm},
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
      await router.push("/clients");
    },
  },
  setup() {
    const route = useRoute();

    return {route};
  },
};
</script>

<style scoped></style>
