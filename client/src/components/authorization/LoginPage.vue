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
import store from "@/store";
import router from "@/router";
import synchronization from "@/services/synchronization";
import authorization from "@/services/authorization";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import TransparentLogoWide from "@/components/ui/TransparentLogoWide.vue";
import GoogleButton from "@/components/ui/GoogleButton.vue";
import LanguageSwitcher from "@/components/ui/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Message from "primevue/message";
import time from "@/services/synchronization/time";

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
        await authorization.login({
          email: this.email,
          password: this.password
        });
        await store.commit("synchronization/setInitialLoaded", false)
        await time.enableServerTimeSync();
        await synchronization.syncInitial();
        await store.commit("synchronization/setInitialLoaded", true)

        await router.push("/");
      } catch (err) {
        this.password = "";
        let message = this.$i18n.t("Unknown error") + ". " + this.$i18n.t("Please try again");
        if (err.message  === "Invalid username or password") {
          message = this.$i18n.t("Invalid email or password");
        }
        this.$toast.add({ severity: "error", summary: this.$i18n.t("Unable to sign in"), detail: message, life: 5000 });
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
