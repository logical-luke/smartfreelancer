<template>
  <form action="#" method="post">
    <div class="container px-4 mx-auto">
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
          />
        </div>

        <div class="mb-6">
          <label>
            <input
              type="checkbox"
              name="field-name"
              value="example value"
              checked
            />
            <span class="ml-2">Remember me</span>
          </label>
        </div>

        <button
          class="inline-block w-full md:w-auto px-6 py-3 font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded transition duration-200"
          type="submit"
        >
          Submit
        </button>
      </form>
    </div>
  </form>
</template>

<script>
import { mapMutations } from "vuex";

export default {
  name: "Login",
  data: () => {
    return {
      email: "",
      password: ""
    };
  },
  methods: {
    ...mapMutations(["setUser", "setToken"]),
    async login(e) {
      e.preventDefault();
      const response = await fetch("http://localhost:8000/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          email: this.email,
          password: this.password
        })
      });
      const { token } = await response.json();
      this.setToken(token);
      this.$router.push("/");
    }
  }
};
</script>

<style scoped></style>
