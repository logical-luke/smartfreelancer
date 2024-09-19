import { defineStore } from 'pinia';
import cookies from "@/services/cookies";
import api from "@/services/api";

interface State {
    token: string | null;
    refreshToken: string | null;
    authorized: boolean;
}

export const useAuthorizationStore = defineStore('authorization', {
    state: (): State => ({
        token: null,
        refreshToken: null,
        authorized: false,
    }),
    getters: {
        isAuthorized: (state) => state.authorized,
        getToken: (state) => state.token,
        getRefreshToken: (state) => state.refreshToken,
    },
    actions: {
        async login(email: string, password: string) {
            const { token, refreshToken } = await api.login(email, password);
            await this.authorize(token, refreshToken);
        },
        async authorize(token: string, refreshToken: string) {
            this.token = token;
            this.refreshToken = refreshToken;
            this.authorized = !!token;
            await cookies.set("api_token", token, "1d", "/", null, true, "Strict");
            await cookies.set("refresh_token", refreshToken, "1d", "/", null, true, "Strict");
        },
        async register(email: string, password: string, confirmPassword: string) {
            await api.register(email, password, confirmPassword);
        },
        async logout() {
            this.token = null;
            this.refreshToken = null;
            this.authorized = false;
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

            this.token = token;
            this.refreshToken = refreshToken;
            this.authorized = !!token;
        },
    },
});
