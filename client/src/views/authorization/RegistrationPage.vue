<script lang="ts" setup>
import {useI18n} from "vue-i18n";
const { t } = useI18n();
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthorizationStore } from "@/stores/auth";
import TransparentLogoWide from "@/components/logo/TransparentLogoWide.vue";
import MainActionButton from "@/components/form/MainActionButton.vue";
import ActionButton from "@/components/form/ActionButton.vue";
import LanguageSwitcher from "@/components/navigation/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Divider from "primevue/divider";
import api from "@/services/api";

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
    // Add toast notification for password mismatch
    return;
  }
  try {
    await authStore.register(email.value, password.value, confirmPassword.value);
    await authStore.login(email.value, password.value);
    await router.push("/");
  } catch (err) {
    // Handle registration error
  }
};

const loginWithGoogle = async () => {
  window.location.href = await api.postGoogleStart();
};

const redirectToLogin = () => {
  router.push("/login");
};
</script>

<template>
  <div class="flex flex-col justify-center items-center h-full min-h-screen p-4 overflow-auto">
    <div class="md:border-2 rounded-md p-8 md:shadow w-full max-w-sm md:max-w-lg lg:max-w-xl">
      <div class="flex justify-center w-full mb-4">
        <TransparentLogoWide size="w-60" text-color="#410B01" />
      </div>
      <div class="flex w-full justify-center items-center">
        <div class="flex flex-col gap-4 w-full">
          <div>
            <label class="block text-sm font-medium mb-1" for="email">
              {{ t("EMAIL") }}
            </label>
            <InputText
              v-model="email"
              class="w-full"
              type="email"
              autocomplete="email"
              :invalid="showEmailValidationFailure"
              @focusout="emailFocused = false"
              @focusin="emailFocused = true"
            />
            <p v-if="showEmailValidationFailure" class="text-red-500 font-bold">
              {{ t("Invalid email") }}
            </p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1" for="password">
              {{ t("PASSWORD") }}
            </label>
            <Password
              id="password-panel"
              v-model="password"
              input-id="password-input"
              :toggle-mask="true"
              autocomplete="new-password"
              prompt-label=" "
              :weak-label="t('Weak password')"
              :medium-label="t('Medium password')"
              :strong-label="t('Strong password')"
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
            <label class="block text-sm font-medium mb-1" for="confirm-password">
              {{ t("CONFIRM PASSWORD") }}
            </label>
            <Password
              id="confirm-password-panel"
              v-model="confirmPassword"
              input-id="confirm-password-input"
              :toggle-mask="true"
              autocomplete="new-password"
              :feedback="false"
            />
          </div>

          <MainActionButton :disabled="isFormValid === false" @click="register">
            {{ t("Sign Up") }}
          </MainActionButton>

          <Divider align="center" class="py-2">
            <span>{{ t("OR") }}</span>
          </Divider>

          <MainActionButton @click="loginWithGoogle">
            {{ t("Sign Up with Google") }}
          </MainActionButton>

          <ActionButton @click="redirectToLogin">
            {{ t("Log In to Your Account") }}
          </ActionButton>

          <div class="flex justify-center mt-4">
            <LanguageSwitcher />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
#password-panel {
  width: 100%;
}

#password-input {
  width: 100%;
}

#confirm-password-panel {
  width: 100%;
}

#confirm-password-input {
  width: 100%;
}
</style>
