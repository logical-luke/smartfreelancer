<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import TransparentLogoWide from "@/components/logo/TransparentLogoWide.vue";
import LanguageSwitcher from "@/components/LanguageSwitcher.vue";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Divider from "primevue/divider";
import ActionButton from "@/components/ActionButton.vue";
import MainActionButton from "@/components/MainActionButton.vue";
import authorization from "@/services/authorization";
import api from "@/services/api";
import { useToast } from "primevue/usetoast";
import i18n from "@/services/locale/i18n";

const router = useRouter();
const store = useStore();
const toast = useToast();

const email = ref<string>("");
const password = ref<string>("");
const buttonTitle = ref<string>("Log in");

async function login() {
  try {
    toast.removeAllGroups();
    const { token, refreshToken } = await authorization.login(email.value, password.value);
    await authorization.authorize(token, refreshToken);

    await router.push("/");
  } catch (err: any) {
    password.value = "";
    let message = i18n.global.t("Unknown error") + ". " + i18n.global.t("Please try again");
    if (err.message === "Invalid username or password") {
      message = i18n.global.t("Invalid email or password");
    }
    toast.add({
      severity: "error",
      summary: i18n.global.t("Unable to sign in"),
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
  <div class="flex flex-col justify-center items-center h-full min-h-screen p-4 overflow-auto">
    <div class="md:border-2 rounded-md p-8 md:shadow w-full max-w-sm md:max-w-lg lg:max-w-xl">
      <div class="flex justify-center w-full mb-4">
        <TransparentLogoWide size="w-60" text-color="#410B01" />
      </div>
      <div class="flex w-full justify-center items-center">
        <div class="flex flex-col gap-4 w-full">
          <div>
            <label class="block text-sm font-medium mb-1" for="email">
              {{ $t("EMAIL") }}
            </label>
            <InputText
                v-model="email"
                class="w-full"
                type="email"
                autocomplete="email"
            />
          </div>

          <div class="w-full" id="password-panel">
            <label class="block text-sm font-medium mb-1" for="password">
              {{ $t("PASSWORD") }}
            </label>
            <Password
                v-model="password"
                :toggle-mask="true"
                autocomplete="current-password"
                :feedback="false"
                inputClass="w-full"
                id="password-input"
            />
          </div>

          <MainActionButton
              :disabled="email === '' || password === ''"
              @click="login">
            {{ $t("Log In") }}
          </MainActionButton>

          <Divider align="center" class="py-2">
            <span>{{ $t("OR") }}</span>
          </Divider>

          <MainActionButton @click="loginWithGoogle">{{ $t("Log In with Google") }}</MainActionButton>

          <ActionButton @click="goToRegistration">{{ $t("Sign Up for an Account") }}</ActionButton>

          <div class="flex justify-center mt-4">
            <LanguageSwitcher />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
#password-panel {
  width: inherit;
}

#password-input {
  width: inherit;
}
</style>
