<script lang="ts" setup>
import {useI18n} from "vue-i18n";

const {t} = useI18n();
import {ref, computed} from "vue";
import {useRouter} from "vue-router";
import {useAuthorizationStore} from "@/stores/auth";
import TransparentLogoWide from "@/components/logo/TransparentLogoWide.vue";
import LanguageSwitcher from "@/components/navigation/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Divider from "primevue/divider";
import {useToast} from "primevue/usetoast";
import isValidEmail from "@/services/isValidEmail";
import ActionButton from "@/components/form/ActionButton.vue";

const toast = useToast();

const email = ref("");
const password = ref("");
const confirmPassword = ref("");
const emailFocused = ref(false);
const router = useRouter();
const authStore = useAuthorizationStore();

const showEmailValidationFailure = computed(() => {
  return !emailFocused.value && email.value !== "" && !isValidEmail(email.value);
});

const isFormValid = computed(() => {
  return isValidEmail(email.value) && password.value !== "" && confirmPassword.value !== "";
});

const register = async () => {
  if (!isFormValid.value) {
    return;
  }
  if (password.value !== confirmPassword.value) {
    confirmPassword.value = "";
    toast.add({
      severity: "error",
      summary: t("Passwords do not match"),
      detail: t("Please try again"),
      life: 5000,
    });
    return;
  }
  try {
    await authStore.register(email.value, password.value, confirmPassword.value);
    await authStore.login(email.value, password.value);
    await router.push("/");
  } catch (err: unknown) {
    let message = t("Unknown error") + ". " + t("Please try again");
    if (err instanceof Error && err.message === "Invalid username or password") {
      message = t("Invalid email or password");
      password.value = "";
      confirmPassword.value = "";
    }
    if (err instanceof Error && err.message === "Account with that email already exists") {
      message = t("Account with that email already exists");
      password.value = "";
      confirmPassword.value = "";
    }
    toast.add({
      severity: "error",
      summary: t("Unable to sign up"),
      detail: message,
      life: 5000,
    });
  }
};

const loginWithGoogle = async () => {
};

const redirectToLogin = () => {
  router.push("/login");
};
</script>

<template>
  <div
      class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 w-full max-w-md">
      <div class="flex justify-center w-full mb-8">
        <TransparentLogoWide/>
      </div>

      <form class="space-y-6" @submit.prevent="register">
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
              :invalid="showEmailValidationFailure"
              @focusout="emailFocused = false"
              @focusin="emailFocused = true"
          />
          <p v-if="showEmailValidationFailure" class="text-red-500 dark:text-red-400 font-bold">
            {{ t("Invalid email") }}
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="password">
            {{ t("PASSWORD") }}
          </label>
          <Password
              id="password"
              v-model="password"
              :toggle-mask="true"
              autocomplete="new-password"
              prompt-label=" "
              :weak-label="t('Weak password')"
              :medium-label="t('Medium password')"
              :strong-label="t('Strong password')"
              class="w-full"
              input-class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              required
              fluid
          >
            <template #header>
              <p class="mb-1 dark:text-gray-300">{{ t("Enter a password") }}</p>
            </template>
            <template #footer="sp">
              {{ sp.level }}
              <Divider/>
              <p class="mt-2 dark:text-gray-300">{{ t("Recommendations") }}:</p>
              <ul class="pl-2 ml-2 mt-0 dark:text-gray-300" style="line-height: 1.5">
                <li>{{ t("At least one lowercase") }}</li>
                <li>{{ t("At least one uppercase") }}</li>
                <li>{{ t("At least one numeric") }}</li>
                <li>{{ t("Minimum 8 characters") }}</li>
              </ul>
            </template>
          </Password>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="confirm-password">
            {{ t("CONFIRM PASSWORD") }}
          </label>
          <Password
              id="confirm-password"
              v-model="confirmPassword"
              :toggle-mask="true"
              autocomplete="new-password"
              :feedback="false"
              class="w-full"
              input-class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              required
              fluid
          />
        </div>

        <ActionButton
            type="primary"
            :fullWidth="true"
            :disabled="!isFormValid"
            @click="register"
        >
          {{ t("Sign Up") }}
        </ActionButton>

        <Divider>
      <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
        {{ t("OR") }}
      </span>
        </Divider>

        <ActionButton
            type="secondary"
            :fullWidth="true"
            @click="loginWithGoogle"
        >
          <i class="pi pi-google mr-2"></i>
          {{ t("Sign Up with Google") }}
        </ActionButton>

        <ActionButton
            type="tertiary"
            :fullWidth="true"
            @click="redirectToLogin"
        >
          {{ t("Log In to Your Account") }}
        </ActionButton>
      </form>

      <div class="flex justify-center mt-6">
        <LanguageSwitcher/>
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
