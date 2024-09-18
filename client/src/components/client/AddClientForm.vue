<script>
import {mapActions, mapState} from "vuex";
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import MailIcon from "vue-tabler-icons/icons/MailIcon";
import PhoneIcon from "vue-tabler-icons/icons/PhoneIcon";
import TrashIcon from "vue-tabler-icons/icons/TrashIcon"
import MainActionButton from "@/components/MainActionButton.vue";
import ActionButton from "@/components/ActionButton.vue";
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
    TrashIcon,
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
      phoneValid: true,
      emailValid: true,
      nameValid: true,
    }
  },
  methods: {
    hasAvatar() {
      return this.client.avatar !== "" && this.client.avatar !== null;
    },
    updateAvatar(avatar) {
      this.client.avatar = avatar;
    },
    clearAvatar() {
      this.client.avatar = "";
    },
    async submitForm() {
      if (!this.canSubmitForm() || this.client.name.length === 0) {
        return;
      }
      try {
        console.log('creating');
        await this.createClient(this.client);
        console.log('created');
        this.$router.push(this.afterSavePageRoute);
      } catch (e) {
        console.log('creating error', e);
      //   todo handle error
      }
    },
    updateEmailValid() {
      const emailRegex = /^\S+@\S+\.\S+$/;
      this.emailValid = this.client.email.length === 0 || emailRegex.test(this.client.email);
    },
    updatePhoneValid() {
      const phoneRegex = /^\+?[0-9]{1,4}[0-9]{6,14}$/;
      this.phoneValid = this.client.phone.length === 0 || phoneRegex.test(this.client.phone);
    },
    updateNameValid() {
      this.nameValid = this.client.name.length > 0;
    },
    canSubmitForm() {
      return this.nameValid && this.emailValid && this.phoneValid;
    },
    ...mapActions({
      createClient: "clients/create",
    }),
  },
};
</script>

<template>
    <div @keyup.enter="submitForm">
      <label class="flex flex-col text-sm font-semibold gap-2" for="name">
        <span>
          {{ $t("Name") }} <span class="text-red-500">*</span>
        </span>
        <input-text
            class="block w-full md:w-1/2 px-4 py-4 text-sm placeholder-gray-500 bg-white border rounded"
            id="name"
            name="name"
            v-model="client.name"
            placeholder="John Doe"
            :invalid="!nameValid"
            @focusout="updateNameValid"
        />
      </label>
    </div>

    <div class="w-full md:w-1/2" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 text-sm font-semibold" for="email">{{ $t("Email") }}
        <input-group>
          <input-group-addon>
            <mail-icon/>
          </input-group-addon>
          <input-text id="email" v-model="client.email" name="email" placeholder="john.doe@domain.com" :invalid="!emailValid" @focusout="updateEmailValid" />
        </input-group>
      </label>
    </div>

    <div class="w-full md:w-1/2" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 text-sm font-semibold" for="phone">{{ $t("Phone") }}
        <input-group>
          <input-group-addon>
            <phone-icon/>
          </input-group-addon>
          <input-text id="phone" name="phone" v-model="client.phone" placeholder="+1 561-555-7689" :invalid="!phoneValid" @focusout="updatePhoneValid" />
        </input-group>
      </label>

    </div>

    <div class="w-full md:w-1/2" @keyup.enter="submitForm">
      <label class="flex flex-col gap-2 text-sm font-semibold" for="phone">{{ $t("Photo") }}
        <image-upload-input v-if="!this.hasAvatar()" @file-uploaded="updateAvatar"/>
        <span class="flex flex-row items-center gap-2" v-else >
          <img :src="client.avatar" alt="avatar" class="w-20 h-20 rounded-full"/>
          <trash-icon @click="clearAvatar" />
        </span>
      </label>
    </div>

    <div class="flex gap-4 flex-col md:flex-row justify-center md:justify-start w-full md:w-1/2">
      <main-action-button :disabled="!canSubmitForm() || client.name.length === 0" @keyup.enter="submitForm" @click="submitForm" class="w-full md:w-auto">
        {{ $t("Add") }}
      </main-action-button>
      <router-link :to="cancelPageRoute">
        <action-button class="w-full md:w-auto">{{ $t("Cancel") }}</action-button>
      </router-link>
    </div>
</template>


