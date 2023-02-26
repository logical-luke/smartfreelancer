<template>
  <div class="flex flex-col justify-center items-center h-screen">
    <div class="flex justify-center w-full">
      <transparent-logo-wide size="w-60" text-color="#410B01" />
    </div>
    <div class="flex w-10/12 md:w-1/4 justify-center items-center">
      <form class="flex w-full flex-col gap-3" @submit.prevent="register">
        <div>
          <label class="block text-sm font-medium mb-2" for="email">
            {{ $t("Email") }}
          </label>
          <input-text
            class="p-inputtext-sm w-full"
            type="email"
            v-model="email"
            name="email"
            autocomplete="email"
          />
        </div>

        <div>
          <label class="block text-sm font-medium mb-2" for="password">
            {{ $t("Password") }}
          </label>
          <password
            class="p-inputtext-sm"
            input-id="passwordInput"
            :toggle-mask="true"
            id="passwordPanel"
            prompt-label=" "
            :weak-label="$t('Weak password')"
            :medium-label="$t('Medium password')"
            :strong-label="$t('Strong password')"
            v-model="password"
          >
            <template #header
            ><p class="mb-1">{{ $t("Enter a password") }}</p></template
            >
            <template #footer="sp">
              {{ sp.level }}
              <divider />
              <p class="mt-2">{{ $t("Recommendations") }}:</p>
              <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                <li>{{ $t("At least one lowercase") }}</li>
                <li>{{ $t("At least one uppercase") }}</li>
                <li>{{ $t("At least one numeric") }}</li>
                <li>{{ $t("Minimum 8 characters") }}</li>
              </ul>
            </template>
          </password>
        </div>

        <div>
          <label class="block text-sm font-medium mb-2" for="password">
            {{ $t("Confirm password") }}
          </label>
          <password
            class="p-inputtext-sm"
            v-model="confirmPassword"
            type="password"
            input-id="confirmPasswordInput"
            :toggle-mask="true"
            id="confirmPasswordPanel"
            :feedback="false"
            name="confirmPassword"
          />
        </div>

        <submit-button>{{ $t("Sign up") }}</submit-button>
        <divider align="center">
          <span>{{ $t("OR") }}</span>
        </divider>
        <google-button>{{ $t("Sign up") }}</google-button>
        <div class="flex">
          <language-switcher />
        </div>
        <div>
          <p class="font-medium text-gray-500">
            {{ $t("Already have an account") }}?
          </p>
          <p class="font-medium text-gray-500">
            {{ $t("Log in") }}
            <router-link class="text-blue-500 font-bold" to="/login">{{
                $t("here")
              }}
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import TransparentLogoWide from "@/components/ui/TransparentLogoWide.vue";
import SubmitButton from "@/components/ui/SubmitButton.vue";
import store from "@/store";
import GoogleButton from "@/components/ui/GoogleButton.vue";
import LanguageSwitcher from "@/components/ui/LanguageSwitcher.vue";
import Password from "primevue/password";
import Divider from "primevue/divider";
import InputText from "primevue/inputtext";
import authorization from "@/services/authorization";
import time from "@/services/synchronization/time";
import synchronization from "@/services/synchronization";
import router from "@/router";

export default {
  name: "RegistrationPage",
  data() {
    return {
      email: "",
      password: "",
      confirmPassword: ""
    };
  },
  methods: {
    async register() {
      this.$toast.removeAllGroups();
      if (this.password !== this.confirmPassword) {
        this.confirmPassword = "";
        this.$toast.add({
          severity: "error",
          summary: this.$i18n.t("Unable to sign up"),
          detail: this.$i18n.t("The passwords given are not the same"),
          life: 5000
        });

        return;
      }
      try {
        await authorization.register({
          email: this.email,
          password: this.password,
          confirmPassword: this.confirmPassword
        });
        const { token, refreshToken } = await authorization.login(this.email, this.password);
        await authorization.authorize(token, refreshToken);
        await store.commit("synchronization/setInitialLoaded", false);
        await time.enableServerTimeSync();
        await synchronization.syncAll();
        await store.commit("synchronization/setInitialLoaded", true);

        await router.push("/");
      } catch (err) {
        let message = this.$i18n.t("Unable to sign up");
        if (err.message === "User already exists") {
          message = this.$i18n.t("Email is already taken");
        }
        this.$toast.add({
          severity: "error",
          summary: this.$i18n.t("Unable to sign up"),
          detail: message,
          life: 5000
        });
      }
    }
  },
  components: {
    LanguageSwitcher,
    GoogleButton,
    TransparentLogoWide,
    SubmitButton,
    Password,
    Divider,
    InputText
  },
  beforeRouteEnter() {
    store.commit("synchronization/setInitialLoaded", true);
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

#confirmPasswordPanel {
  width: 100%;
}

#confirmPasswordInput {
  width: 100%;
}
</style>
