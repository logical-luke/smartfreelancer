import { defineStore } from 'pinia';
import { useCookies } from "vue3-cookies";
import api from "@/services/api";

const { cookies } = useCookies();

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
            const { token, refreshToken } = await api.auth.login(email, password);
            await this.authorize(token, refreshToken);
        },
        async authorize(token: string, refreshToken: string) {
            this.token = token;
            this.refreshToken = refreshToken;
            this.authorized = !!token;
            cookies.set("api_token", token, "1d", "/", undefined, true, "Strict");
            cookies.set("refresh_token", refreshToken, "1d", "/", undefined, true, "Strict");
        },
        async register(email: string, password: string, confirmPassword: string) {
            await api.auth.register(email, password, confirmPassword);
        },
        async logout() {
            this.token = null;
            this.refreshToken = null;
            this.authorized = false;
            cookies.remove("api_token");
            cookies.remove("refresh_token");
        },
        async getTokensFromCookies() {
            let token: string|null = cookies.get("api_token");
            if (token === "null" || token === "") {
                token = null;
            }

            let refreshToken: string|null = cookies.get("refresh_token");
            if (refreshToken === "null" || refreshToken === "") {
                refreshToken = null;
            }

            this.token = token;
            this.refreshToken = refreshToken;
            this.authorized = !!token;
        },
    },
});
