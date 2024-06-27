<template>
  <div class="flex flex-col justify-center items-center h-full min-h-screen p-4 overflow-auto">
    <div class="md:border-2 rounded-md p-8 md:shadow w-full max-w-sm md:max-w-lg lg:max-w-xl">
      <div class="flex justify-center w-full mb-4">
        <transparent-logo-wide size="w-60" text-color="#410B01" />
      </div>
      <div class="flex w-full justify-center items-center">
        <div class="flex flex-col gap-4 w-full">
          <div>
            <label class="block text-sm font-medium mb-1" for="email">
              {{ $t("EMAIL") }}
            </label>
            <input-text
                class="w-full p-inputtext-sm"
                type="email"
                v-model="email"
                autocomplete="email"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-1" for="password">
              {{ $t("PASSWORD") }}
            </label>
            <password
                class="w-full p-inputtext-sm"
                v-model="password"
                input-id="passwordInput"
                :toggle-mask="true"
                autocomplete="current-password"
                id="passwordPanel"
                :feedback="false"
            />
          </div>

          <main-action-button @click="login">{{ $t("Log In") }}</main-action-button>

          <divider align="center" class="py-2">
            <span>{{ $t("OR") }}</span>
          </divider>

          <main-action-button @click="loginWithGoogle">{{ $t("Log In with Google") }}</main-action-button>

          <action-button @click="goToRegistration">{{ $t("Sign Up for an Account") }}</action-button>

          <div class="flex justify-center mt-4">
            <language-switcher />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import store from "@/store";
import router from "@/router";
import synchronization from "@/services/synchronization";
import authorization from "@/services/authorization";
import SubmitButton from "@/components/SubmitButton.vue";
import TransparentLogoWide from "@/components/logo/TransparentLogoWide.vue";
import GoogleButton from "@/components/GoogleButton.vue";
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Divider from "primevue/divider";
import ActionButton from "@/components/ActionButton.vue";
import MainActionButton from "@/components/MainActionButton.vue";
import api from "@/services/api";

export default {
  name: "LoginPage",
  components: {
    MainActionButton,
    ActionButton,
    LanguageSwitcher,
    GoogleButton,
    TransparentLogoWide,
    SubmitButton,
    Password,
    InputText,
    Divider
  },
  data: () => {
    return {
      email: "",
      password: "",
      buttonTitle: "Log in",
    };
  },
  methods: {
    async login() {
      try {
        this.$toast.removeAllGroups();
        const { token, refreshToken } = await authorization.login(
            this.email,
            this.password
        );
        await authorization.authorize(token, refreshToken);
        await store.commit("synchronization/setInitialLoaded", false);
        await synchronization.fetchUser();
        await synchronization.fetchAllData();
        await store.commit("synchronization/setInitialLoaded", true);

        await router.push("/");
      } catch (err) {
        this.password = "";
        let message =
            this.$i18n.t("Unknown error") +
            ". " +
            this.$i18n.t("Please try again");
        if (err.message === "Invalid username or password") {
          message = this.$i18n.t("Invalid email or password");
        }
        this.$toast.add({
          severity: "error",
          summary: this.$i18n.t("Unable to sign in"),
          detail: message,
          life: 5000,
        });
      }
    },
    async loginWithGoogle() {
      window.location.href = await api.postGoogleStart()
    },
    async goToRegistration() {
      await router.push("/register");
    },
  },
  beforeRouteEnter() {
    store.commit("synchronization/setInitialLoaded", true);
  },
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
