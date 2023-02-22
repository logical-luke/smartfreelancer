<template>
  <div class="flex flex-col justify-center items-center h-screen">
    <div class="flex justify-center w-full">
      <transparent-logo-wide size="w-60" text-color="#410B01" />
    </div>
    <div class="flex justify-center items-center">
      <transition name="fade">
        <div v-if="error" class="pb-2">
          <div
            class="p-6 border-l-4 border-orange-600 bg-orange-500 rounded-r-lg"
          >
            <div class="flex items-center">
              <h3 class="text-white font-medium">{{ error }}</h3>
              <button class="ml-auto" @click.prevent="clearError">
                <svg
                  class="text-orange-800"
                  width="12"
                  height="12"
                  viewBox="0 0 12 12"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M6.93341 6.00008L11.1334 1.80008C11.4001 1.53341 11.4001 1.13341 11.1334 0.866748C10.8667 0.600081 10.4667 0.600081 10.2001 0.866748L6.00008 5.06675L1.80008 0.866748C1.53341 0.600081 1.13341 0.600081 0.866748 0.866748C0.600082 1.13341 0.600082 1.53341 0.866748 1.80008L5.06675 6.00008L0.866748 10.2001C0.733415 10.3334 0.666748 10.4667 0.666748 10.6667C0.666748 11.0667 0.933415 11.3334 1.33341 11.3334C1.53341 11.3334 1.66675 11.2667 1.80008 11.1334L6.00008 6.93341L10.2001 11.1334C10.3334 11.2667 10.4667 11.3334 10.6667 11.3334C10.8667 11.3334 11.0001 11.2667 11.1334 11.1334C11.4001 10.8667 11.4001 10.4667 11.1334 10.2001L6.93341 6.00008Z"
                    fill="currentColor"
                  ></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </transition>
      <form @submit.prevent="login">
        <div>
          <label class="block text-sm font-medium mb-2" for="email">
            {{ $t("Email") }}
          </label>
          <input
            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
            type="email"
            v-model="email"
            name="email"
            placeholder="username@domain.com"
            autocomplete="email"
          />
        </div>

        <div>
          <label class="block text-sm font-medium mb-2" for="password">
            {{ $t("Password") }}
          </label>
          <input
            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
            v-model="password"
            type="password"
            name="password"
            autocomplete="password"
          />
        </div>

        <div class="flex flex-wrap gap-2 items-center">
          <submit-button>{{ $t("Log in") }}</submit-button>
          <google-button>{{ $t("Log in") }}</google-button>
        </div>
        <div class="flex pt-2">
          <language-switcher />
        </div>
        <p class="mb-2 text-base text-gray-500 mt-2">
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

export default {
  name: "LoginPage",
  components: {
    LanguageSwitcher,
    GoogleButton,
    TransparentLogoWide,
    SubmitButton
  },
  data: () => {
    return {
      email: "",
      password: "",
      buttonTitle: "Log in",
      error: null
    };
  },
  methods: {
    clearError() {
      this.error = null;
    },
    async login() {
      try {
        await store.dispatch("login", {
          email: this.email,
          password: this.password
        });
      } catch (err) {
        this.email = "";
        this.password = "";
        this.error = err.message;
      }
    }
  }
};
</script>

<style scoped></style>
