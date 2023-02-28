<template>
  <div class="flex flex-col justify-center items-center h-screen">
    <div class="flex justify-center w-full">
      <transparent-logo-wide size="w-60" text-color="#410B01" />
    </div>
    <div class="flex w-10/12 md:w-1/4 justify-center items-center">
      <form class="flex w-3/4 flex-col gap-3" @submit.prevent="login">
        <div>
          <label class="block text-sm font-medium mb-2" for="email">
            {{ $t("Email") }}
          </label>
          <input-text
            class="w-full p-inputtext-sm"
            type="email"
            v-model="email"
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
            autocomplete="current-password"
            id="passwordPanel"
            :feedback="false"
          />
        </div>

        <submit-button>{{ $t("Log in") }}</submit-button>
        <divider align="center">
          <span>{{ $t("OR") }}</span>
        </divider>

        <google-button>{{ $t("Log in") }}</google-button>
        <div class="flex">
          <language-switcher />
        </div>
        <div>
          <p class="font-medium text-gray-500">
            {{ $t("Don't have an account") }}?
          </p>
          <p class="font-medium text-gray-500">
            {{ $t("Create a new account") }}
            <router-link class="text-blue-500 font-bold" to="/register"
              >{{ $t("here") }}
            </router-link>
          </p>
        </div>
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
import Divider from "primevue/divider";

export default {
  name: "LoginPage",
  components: {
    LanguageSwitcher,
    GoogleButton,
    TransparentLogoWide,
    SubmitButton,
    Password,
    InputText,
    Divider,
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
        await synchronization.syncUser();
        await synchronization.syncAll();
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
