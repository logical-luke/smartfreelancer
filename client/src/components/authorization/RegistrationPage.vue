<template>
  <div class="flex flex-col justify-center items-center h-screen">
    <div class="flex justify-center w-full">
      <transparent-logo-wide size="w-60" text-color="#410B01" />
    </div>
    <div class="flex justify-center items-center px-4 md:px-0">
      <form class="flex flex-col gap-3" @submit.prevent="register">
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
            <template #header><p class="mb-1">{{ $t("Enter a password")}}</p></template>
            <template #footer="sp">
              {{sp.level}}
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
            :toggle-mask="true"
            :feedback="false"
            name="confirmPassword"
          />
        </div>

        <div class="flex flex-wrap gap-2 items-center">
          <submit-button>{{ $t("Sign in ") }}</submit-button>
          <google-button>{{ $t("Sign in") }}</google-button>
        </div>
        <div class="flex">
          <language-switcher />
        </div>
        <p class="mb-2 font-medium text-gray-500">
          {{ $t("Already have an account") }}? {{ $t("Log in") }}
          <router-link class="text-blue-500" to="/login">{{ $t("here") }}</router-link>
        </p>
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
import Password from 'primevue/password';
import Divider from 'primevue/divider';
import InputText from 'primevue/inputtext';

export default {
  name: "RegistrationPage",
  data() {
    return {
      email: "",
      password: "",
      confirmPassword: "",
      error: null,
      passwordStrength: "",
    };
  },
  methods: {
    clearError() {
      this.error = null;
    },
    async register() {
      try {
        await store.dispatch("register", {
          email: this.email,
          password: this.password,
          confirmPassword: this.confirmPassword
        });
      } catch (err) {
        this.error = err.message;
      }
    },
  },
  components: {
    LanguageSwitcher,
    GoogleButton,
    TransparentLogoWide,
    SubmitButton,
    Password,
    Divider,
    InputText,
  },
  beforeRouteEnter() {
    store.commit("setInitialLoaded", true);
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
