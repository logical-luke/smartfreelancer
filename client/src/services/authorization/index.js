import cookies from "@/services/cookies";
import cache from "@/services/cache";
import router from "@/router";
import store from "@/store";
import api from "@/services/api";

export default {
  async login(email, password) {
    try {
      const { token, refreshToken } = await api.login(email, password);
      store.commit("authorization/setToken", token);
      store.commit("authorization/setRefreshToken", refreshToken);
    } catch (error) {
      throw new Error(error.message);
    }
  },
  async authorize(token, refreshToken) {
    store.commit("authorization/setToken", token);
    store.commit("authorization/setRefreshToken", refreshToken);
    store.commit("authorization/setAuthorized", true);
  },
  async register(credentials) {
    try {
      await api.register(
        credentials.email,
        credentials.password,
        credentials.confirmPassword
      );
    } catch (err) {
      throw new Error(err.message);
    }
  },
  async logout() {
    await cookies.set("api_token", "", "1d", "/", null, true, "Strict");
    await store.commit("authorization/setToken", "");
    await cookies.set(
      "refresh_token",
      "",
      "1d",
      "/",
      null,
      true,
      "Strict"
    );
    await store.commit("authorization/setRefreshToken", "");
    await store.commit("authorization/setAuthorized", false);
    await store.commit("setUser", {});
    await store.commit("synchronization/setSynchronizationTime", null);
    await cookies.remove("token");
    await cookies.remove("refresh_token");
    await cache.clear();
  },
}
