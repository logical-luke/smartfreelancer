<template>
  <div>
    <label class="flex flex-col text-sm font-semibold gap-2" for="name">
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
    <label class="flex flex-col gap-2 text-sm font-semibold" for="email">{{ $t("Email") }}
      <input-group>
        <input-group-addon>
          <mail-icon/>
        </input-group-addon>
        <input-text id="email" name="email" placeholder="john.doe@domain.com"/>
      </input-group>
    </label>
  </div>

  <div class="w-full md:w-1/2">
    <label class="flex flex-col gap-2 text-sm font-semibold" for="phone">{{ $t("Phone") }}
      <input-group>
        <input-group-addon>
          <phone-icon/>
        </input-group-addon>
        <input-text id="phone" name="phone" placeholder="+1 561-555-7689"/>
      </input-group>
    </label>

  </div>

  <div class="w-full md:w-1/2">
    <label class="flex flex-col gap-2 text-sm font-semibold" for="phone">{{ $t("Photo") }}
      <file-upload  v-if="!hasAvatar()" mode="basic"
                   class="inline-flex
                  shadow
                  p-4
                  min-w-40
                  justify-center
                  flex-nowrap
                  items-center
                  text-sm
                  font-medium
                  bg-white
                  border-2
                  border-indigo-500
                  text-black
                  hover:bg-indigo-500
                  hover:text-white
                  rounded-md
                  disabled:bg-slate-50
                  disabled:text-slate-500
                  transition
                  duration-200"
                   name="image"
                   :url="uploadApiURL"
                   accept="image/*"
                   :maxFileSize="1000000"
                   @upload="onUpload"
                   :auto="true"
                   :chooseLabel="chooseLabel"
                   @before-send="beforeSend"
      />
      <img v-else :src="client.avatar" alt="avatar" class="w-20 h-20 rounded-full"/>
    </label>
  </div>

  <div class="flex gap-4 flex-col md:flex-row justify-center md:justify-start w-full md:w-1/2">
    <main-action-button @click="submitForm" class="w-full md:w-auto">
      {{ $t("Add Client") }}
    </main-action-button>
    <router-link :to="this.clientsPageRoute">
      <action-button class="w-full md:w-auto">{{ $t("Cancel") }}</action-button>
    </router-link>
  </div>
</template>

<script>
import {mapState} from "vuex";
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import MailIcon from "vue-tabler-icons/icons/MailIcon";
import PhoneIcon from "vue-tabler-icons/icons/PhoneIcon";
import FileUpload from 'primevue/fileupload';
import store from "@/store";
import MainActionButton from "@/components/MainActionButton.vue";
import ActionButton from "@/components/ActionButton.vue";
import client from "@/services/client";

export default {
  name: "ClientForm",
  components: {ActionButton, MainActionButton, MailIcon, InputText, InputGroup, InputGroupAddon, PhoneIcon, FileUpload},
  data() {
    return {
      client: {
        name: "",
        avatar: "",
        email: "",
        phone: ""
      }
    }
  },
  computed: {
    chooseLabel() {
      return this.$t("Browse");
    },
    uploadApiURL() {
      return process.env.API_BASE_URL + "/upload/image";
    }
  },
  methods: {
    onUpload(event) {
      const response = JSON.parse(event.xhr.response);

      if (response.filename) {
        this.client.avatar = response.filename;
      }
    },
    hasAvatar() {
      return this.client.avatar !== "";
    },
    beforeSend(request) {
      request.xhr.setRequestHeader('Authorization', 'Bearer ' + store.getters["authorization/getToken"]);

      return request;
    },
    async submitForm() {
      await client.addClient(this.client)

      this.$router.push("/clients");
    },
  }
};
</script>

<style scoped></style>
