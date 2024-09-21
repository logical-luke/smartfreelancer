<script lang="ts" setup>
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthorizationStore } from "@/stores/auth";
import TransparentLogoWide from "@/components/logo/TransparentLogoWide.vue";
import MainActionButton from "@/components/form/PrimaryActionButton.vue";
import ActionButton from "@/components/form/SecondaryActionButton.vue";
import LanguageSwitcher from "@/components/navigation/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Divider from "primevue/divider";
import api from "@/services/api";
import { useToast } from "primevue/usetoast";

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

const isValidEmail = (email: string) => {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
};

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
  await authStore.register(email.value, password.value, confirmPassword.value);
  await authStore.login(email.value, password.value);
  await router.push("/");
};

const loginWithGoogle = async () => {
  window.location.href = await api.postGoogleStart();
};

const redirectToLogin = () => {
  router.push("/login");
};
</script>

<template>
  <div class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-br from-indigo-100 to-purple-100 p-4">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
      <div class="flex justify-center w-full mb-8">
        <TransparentLogoWide />
      </div>

      <form @submit.prevent="register" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
            {{ t("EMAIL") }}
          </label>
          <InputText
              v-model="email"
              id="email"
              class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
              type="email"
              autocomplete="email"
              required
              :invalid="showEmailValidationFailure"
              @focusout="emailFocused = false"
              @focusin="emailFocused = true"
          />
          <p v-if="showEmailValidationFailure" class="text-red-500 font-bold">
            {{ t("Invalid email") }}
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1" for="password">
            {{ t("PASSWORD") }}
          </label>
          <Password
              v-model="password"
              id="password"
              :toggle-mask="true"
              autocomplete="new-password"
              prompt-label=" "
              :weak-label="t('Weak password')"
              :medium-label="t('Medium password')"
              :strong-label="t('Strong password')"
              class="w-full"
              inputClass="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
              required
              fluid
          >
            <template #header>
              <p class="mb-1">{{ t("Enter a password") }}</p>
            </template>
            <template #footer="sp">
              {{ sp.level }}
              <Divider />
              <p class="mt-2">{{ t("Recommendations") }}:</p>
              <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                <li>{{ t("At least one lowercase") }}</li>
                <li>{{ t("At least one uppercase") }}</li>
                <li>{{ t("At least one numeric") }}</li>
                <li>{{ t("Minimum 8 characters") }}</li>
              </ul>
            </template>
          </Password>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1" for="confirm-password">
            {{ t("CONFIRM PASSWORD") }}
          </label>
          <Password
              v-model="confirmPassword"
              id="confirm-password"
              :toggle-mask="true"
              autocomplete="new-password"
              :feedback="false"
              class="w-full"
              inputClass="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
              required
              fluid
          />
        </div>

        <MainActionButton
            type="submit"
            :disabled="!isFormValid"
            :full-width="true"
        >
          {{ t("Sign Up") }}
        </MainActionButton>

        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">
              {{ t("OR") }}
            </span>
          </div>
        </div>

        <MainActionButton :full-width="true" @click="loginWithGoogle" type="button">
          <i class="pi pi-google mr-2"></i>
          {{ t("Sign Up with Google") }}
        </MainActionButton>

        <ActionButton :full-width="true" @click="redirectToLogin" type="button">
          {{ t("Log In to Your Account") }}
        </ActionButton>
      </form>

      <div class="flex justify-center mt-6">
        <LanguageSwitcher />
      </div>
    </div>
  </div>
</template>
