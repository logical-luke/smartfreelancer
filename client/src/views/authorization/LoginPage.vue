<script setup lang="ts">
import { useI18n} from "vue-i18n";
const { t } = useI18n();
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import TransparentLogoWide from "@/components/logo/TransparentLogoWide.vue";
import LanguageSwitcher from "@/components/navigation/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Divider from "primevue/divider";
import ActionButton from "@/components/form/SecondaryActionButton.vue";
import MainActionButton from "@/components/form/PrimaryActionButton.vue";
import { useAuthorizationStore } from "@/stores/auth";
import api from "@/services/api";
import isValidEmail from "@/services/isValidEmail";

const router = useRouter();
const toast = useToast();
const authorizationStore = useAuthorizationStore();

const email = ref<string>("");
const password = ref<string>("");

async function login() {
  try {
    toast.removeAllGroups();
    await authorizationStore.login(email.value, password.value);
    await router.push("/");
  } catch (err: unknown) {
    password.value = "";
    let message = t("Unknown error") + ". " + t("Please try again");
    if (err instanceof Error && err.message === "Invalid username or password") {
      message = t("Invalid email or password");
    }
    toast.add({
      severity: "error",
      summary: t("Unable to sign in"),
      detail: message,
      life: 5000,
    });
  }
}

async function loginWithGoogle() {
  window.location.href = await api.postGoogleStart();
}

async function goToRegistration() {
  await router.push("/register");
}
</script>

<template>
  <div class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 w-full max-w-md">
      <div class="flex justify-center w-full mb-8">
        <TransparentLogoWide />
      </div>

      <form class="space-y-6" @submit.prevent="login">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="email">
            {{ t("EMAIL") }}
          </label>
          <InputText
              id="email"
              v-model="email"
              class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              type="email"
              autocomplete="email"
              required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="password">
            {{ t("PASSWORD") }}
          </label>
          <Password
              id="password"
              v-model="password"
              :toggle-mask="true"
              autocomplete="current-password"
              :feedback="false"
              class="w-full"
              input-class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              required
          />
        </div>

        <MainActionButton
            type="submit"
            :disabled="email === '' || password === '' || (email.length > 0 && !isValidEmail(email))"
            :full-width="true"
        >
          {{ t("Log In") }}
        </MainActionButton>

          <Divider>
            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
              {{ t("OR") }}
            </span>
          </Divider>

        <MainActionButton :full-width="true" type="button" @click="loginWithGoogle">
          <i class="pi pi-google mr-2"></i>
          {{ t("Log In with Google") }}
        </MainActionButton>

        <ActionButton :full-width="true" type="button" @click="goToRegistration">
          {{ t("Sign Up for an Account") }}
        </ActionButton>
      </form>

      <div class="flex justify-center mt-6">
        <LanguageSwitcher />
      </div>
    </div>
  </div>
</template>

<style>
.p-divider.p-divider-horizontal {
  @apply bg-white dark:bg-gray-800;
}

.p-divider.p-divider-horizontal:before {
  @apply border-gray-300 dark:border-gray-700;
}

.p-divider .p-divider-content {
  @apply bg-white dark:bg-gray-800;
}
</style>

