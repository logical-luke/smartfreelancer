<template>
  <form action="#" method="post">
    <div class="container px-4 py-16 md:px-72 md:py-72 mx-auto">
      <form @submit.prevent="login">
        <div class="mb-6">
          <label class="block text-sm font-medium mb-2" for="email">
            Email
          </label>
          <input
            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
            type="text"
            v-model="email"
            name="email"
            placeholder="username@email.com"
            autocomplete="email"
          />
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium mb-2" for="password">
            Password
          </label>
          <input
            class="block w-full px-4 py-3 mb-2 text-sm placeholder-gray-500 bg-white border rounded"
            v-model="password"
            type="password"
            name="password"
            autocomplete="password"
          />
        </div>

        <button
          class="inline-block w-full md:w-auto px-6 py-3 font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
          type="submit"
        >
          Log in
        </button>
      </form>
    </div>
  </form>
</template>

<script>
import { mapMutations } from "vuex";
import api from "@/api/api";

export default {
  name: "Login",
  data: () => {
    return {
      email: "",
      password: ""
    };
  },
  methods: {
    ...mapMutations(["setRefreshToken", "setToken", "setAuthorized"]),
    async login(e) {
      e.preventDefault();
      // try {
      const { token, refreshToken } = await api.login(this.email, this.password);
      // } catch (err) {

      // }
      this.setToken(token);
      this.setRefreshToken(refreshToken);
      this.setAuthorized(true);
      this.$router.push("/");
    }
  }
};
</script>

<style scoped></style>
