import cookies from "@/services/cookies";
import store from "@/store";
import api from "@/services/api";

export default {
  async login(email, password) {
    try {
      const { token, refreshToken } = await api.login(email, password);

      return { token, refreshToken };
    } catch (error) {
      throw new Error(error.message);
    }
  },
  async authorize(token, refreshToken) {
    store.commit("authorization/setToken", token);
    await cookies.set("api_token", token, "1d", "/", null, true, "Strict");
    store.commit("authorization/setRefreshToken", refreshToken);
    await cookies.set(
      "refresh_token",
      refreshToken,
      "1d",
      "/",
      null,
      true,
      "Strict"
    );
    if (token) {
      store.commit("authorization/setAuthorized", true);
    }
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
    await store.commit("authorization/setToken", null);
    await store.commit("authorization/setRefreshToken", null);
    await store.commit("authorization/setAuthorized", false);
    await store.commit("setUser", {});
    await store.commit("clients/setClients", []);
    await store.commit("projects/setProjects", []);
    await store.commit("tasks/setTasks", []);
    await store.commit("timer/clearTimer");
    await cookies.remove("api_token");
    await cookies.remove("refresh_token");
  },
  async getTokensFromCookies() {
    let token = await cookies.get("api_token");
    if (token === "null" || token === "") {
      token = null;
    }

    let refreshToken = await cookies.get("refresh_token");
    if (refreshToken === "null" || refreshToken === "") {
      refreshToken = null;
    }

    return { token, refreshToken };
  },
};
