<template>
  <div class="flex mb-8">
    <div>
      <h3 class="text-xl font-bold">{{ $t("Add Client") }}</h3>
    </div>
  </div>
  <div class="flex container">
    <div class="flex flex-col gap-4 w-full">
      <div>
        <label class="flex flex-col text-sm font-medium gap-2" for="name">
          {{ $t("Name") }}
          <input-text
              class="block w-full md:w-1/2 px-4 py-4 text-sm placeholder-gray-500 bg-white border rounded"
              id="name"
              name="name"
              v-model="client.name"
              placeholder="John Doe"
          />
        </label>
      </div>

      <div class="w-full md:w-1/2">
        <label class="flex flex-col gap-2 text-sm font-medium" for="email">{{ $t("Email") }}
          <input-group>
            <input-group-addon>
              <mail-icon/>
            </input-group-addon>
            <input-text id="email" name="email" placeholder="john.doe@domain.com"/>
          </input-group>
        </label>
      </div>

      <div class="w-full md:w-1/2">
        <label class="flex flex-col gap-2 text-sm font-medium" for="phone">{{ $t("Phone") }}
          <input-group>
            <input-group-addon>
              <phone-icon/>
            </input-group-addon>
            <input-text id="phone" name="phone" placeholder="+1 561-555-7689"/>
          </input-group>
        </label>

      </div>

      <div>
        <label class="flex flex-col gap-2 text-sm font-medium" for="description">
          {{ $t("Description") }}
          <textarea
              class="block w-full md:w-1/2 px-4 py-4 text-sm placeholder-gray-500 bg-white border rounded"
              id="description"
              name="description"
              v-model="client.description"
              placeholder="Started as small business working on..."
              rows="5"
          ></textarea>
        </label>
      </div>

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
import {mapState} from "vuex";
import SubmitButton from "@/components/SubmitButton.vue";
import SquarePlusIcon from "vue-tabler-icons/icons/SquarePlusIcon";
import ActionButton from "@/components/ActionButton.vue";
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import MailIcon from "vue-tabler-icons/icons/MailIcon";
import PhoneIcon from "vue-tabler-icons/icons/PhoneIcon";
import MainActionButton from "@/components/MainActionButton.vue";

export default {
  name: "AddClientPage",
  components: {
    MainActionButton,
    PhoneIcon, ActionButton, SquarePlusIcon, SubmitButton, ClientForm, InputText, InputGroupAddon, InputGroup, MailIcon
  },
  data() {
    return {
      clientsPageRoute: {
        name: "ClientsPage"
      },
      client: {
        name: "",
        description: "",
        email: "",
        phone: ""
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
