<template>
  <div class="flex flex-col justify-center items-center h-screen">
    <div class="flex justify-center w-full">
      <transparent-logo-wide size="w-60" text-color="#410B01" />
    </div>
    <div class="flex justify-center items-center px-4 md:px-0">
      <form class="flex flex-col gap-3" @submit.prevent="login">
        <div>
          <label class="block text-sm font-medium mb-2" for="email">
            {{ $t("Email") }}
          </label>
          <input-text
            class="w-full p-inputtext-sm"
            type="email"
            v-model="email"
            name="email"
            autocomplete="email"
          />
        </div>

        <div>
          <label class="block text-sm font-medium" for="password">
            {{ $t("Password") }}
          </label>
          <password
            class="p-inputtext-sm"
            v-model="password"
            input-id="passwordInput"
            :toggle-mask="true"
            autocomplete="password"
            id="passwordPanel"
            :feedback="false"
          />
        </div>

        <div class="flex flex-wrap gap-2 items-center">
          <submit-button>{{ $t("Log in") }}</submit-button>
          <google-button>{{ $t("Log in") }}</google-button>
        </div>
        <div class="flex">
          <language-switcher />
        </div>
        <p class="mb-2 font-medium text-gray-500">
          {{ $t("Don't have an account") }}? {{ $t("Create a new account") }}
          <router-link class="text-blue-500" to="/register">{{ $t("here") }}</router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script>
import SubmitButton from "@/components/ui/SubmitButton.vue";
import store from "@/store";
import TransparentLogoWide from "@/components/ui/TransparentLogoWide.vue";
import GoogleButton from "@/components/ui/GoogleButton.vue";
import LanguageSwitcher from "@/components/ui/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Message from "primevue/message";

export default {
  name: "LoginPage",
  components: {
    LanguageSwitcher,
    GoogleButton,
    TransparentLogoWide,
    SubmitButton,
    Password,
    InputText,
    Message
  },
  data: () => {
    return {
      email: "",
      password: "",
      buttonTitle: "Log in"
    };
  },
  methods: {
    async login() {
      try {
        this.$toast.removeAllGroups();
        await store.dispatch("login", {
          email: this.email,
          password: this.password
        });
      } catch (err) {
        this.$toast.add({ severity: "error", summary: "Unable to log in", detail: err.message, life: 5000 });
      }
    }
  }
};
</script>

<style>
#passwordPanel {
  width: 100%;
}

#passwordInput {
  width: 100%;
}
</style>
