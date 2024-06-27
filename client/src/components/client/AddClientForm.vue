<script>
import {mapState} from "vuex";
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import MailIcon from "vue-tabler-icons/icons/MailIcon";
import PhoneIcon from "vue-tabler-icons/icons/PhoneIcon";
import store from "@/store";
import MainActionButton from "@/components/MainActionButton.vue";
import ActionButton from "@/components/ActionButton.vue";
import client from "@/services/client";
import ImageUploadInput from "@/components/ImageUploadInput.vue";

export default {
  name: "AddClientForm",
  components: {
    ImageUploadInput,
    ActionButton,
    MainActionButton,
    MailIcon,
    InputText,
    InputGroup,
    InputGroupAddon,
    PhoneIcon,
  },
  data() {
    return {
      client: {
        name: "",
        avatar: "",
        email: "",
        phone: ""
      },
      cancelPageRoute: {
        name: "ClientsPage"
      },
      afterSavePageRoute: {
        name: "ClientsPage"
      },
    }
  },
  methods: {
    hasAvatar() {
      return this.client.avatar !== "";
    },
    updateAvatar(avatar) {
      this.client.avatar = avatar;
    },
    async submitForm() {
      await client.add(this.client)

      this.$router.push(this.afterSavePageRoute);
    },
  },
};
</script>

<template>
    <div @keyup.enter="submitForm">
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

    <div class="w-full md:w-1/2" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 text-sm font-semibold" for="email">{{ $t("Email") }}
        <input-group>
          <input-group-addon>
            <mail-icon/>
          </input-group-addon>
          <input-text id="email" name="email" placeholder="john.doe@domain.com"/>
        </input-group>
      </label>
    </div>

    <div class="w-full md:w-1/2" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 text-sm font-semibold" for="phone">{{ $t("Phone") }}
        <input-group>
          <input-group-addon>
            <phone-icon/>
          </input-group-addon>
          <input-text id="phone" name="phone" placeholder="+1 561-555-7689"/>
        </input-group>
      </label>

    </div>

    <div class="w-full md:w-1/2" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 text-sm font-semibold" for="phone">{{ $t("Photo") }}
        <image-upload-input v-if="!this.hasAvatar()" @file-uploaded="updateAvatar"/>
        <img v-else :src="client.avatar" alt="avatar" class="w-20 h-20 rounded-full"/>
      </label>
    </div>

    <div class="flex gap-4 flex-col md:flex-row justify-center md:justify-start w-full md:w-1/2">
      <main-action-button @keyup.enter="submitForm" @click="submitForm" class="w-full md:w-auto">
        {{ $t("Add Client") }}
      </main-action-button>
      <router-link :to="cancelPageRoute">
        <action-button class="w-full md:w-auto">{{ $t("Cancel") }}</action-button>
      </router-link>
    </div>
</template>


