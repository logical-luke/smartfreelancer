import axios from "axios";
import type { AxiosResponse } from "axios";
import { useAuthorizationStore } from "@/stores/auth";

axios.defaults.withCredentials = true;

export default {
    async get(
        url: string,
        params: Record<string, string> = {},
        headers: Record<string, string> = {},
        repeated = 0
    ): Promise<AxiosResponse<any>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.get(process.env.API_BASE_URL + url, {
                params: params,
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`,
                },
            });

            if (response.status === 404) {
                return { data: {}, status: 404 } as AxiosResponse<any>;
            }

            if (response.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {}, status: 401 } as AxiosResponse<any>;
                }

                await this.refreshToken();

                repeated++;
                return this.get(url, params, headers, repeated);
            }

            return response;
        } catch (err: any) {
            if (err.response?.status === 404) {
                return { data: {}, status: 404 } as AxiosResponse<any>;
            }

            if (err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {}, status: 401 } as AxiosResponse<any>;
                }

                await this.refreshToken();

                repeated++;
                return this.get(url, params, headers, repeated);
            }
        }

        if (repeated > 3) {
            throw new Error("Too many failed requests");
        }

        repeated++;

        return this.get(url, params, headers, repeated);
    },
    async post(
        url: string,
        data: Record<string, any> = {},
        headers: Record<string, any> = {},
        repeated = 0
    ): Promise<AxiosResponse<any>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.post(process.env.API_BASE_URL + url, data, {
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`,
                },
            });

            if (response.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {}, status: 401 } as AxiosResponse<any>;
                }
                await this.refreshToken();

                repeated++;
                return this.post(url, data, headers, repeated);
            }

            return response;
        } catch (err: any) {
            if (err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {}, status: 401 } as AxiosResponse<any>;
                }
                await this.refreshToken();

                repeated++;
                return this.post(url, data, headers, repeated);
            }
        }

        if (repeated > 3) {
            throw new Error("Too many failed requests");
        }

        repeated++;
        return this.post(url, data, headers, repeated);
    },
    async put(
        url: string,
        data: Record<string, any> = {},
        headers: Record<string, any> = {},
        repeated = 0
    ): Promise<AxiosResponse<any>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.put(process.env.API_BASE_URL + url, data, {
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`,
                },
            });

            if (response.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {}, status: 401 } as AxiosResponse<any>;
                }
                await this.refreshToken();

                repeated++;
                return this.put(url, data, headers, repeated);
            }

            return response;
        } catch (err: any) {
            if (err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {}, status: 401 } as AxiosResponse<any>;
                }
                await this.refreshToken();

                repeated++;
                return this.put(url, data, headers, repeated);
            }
        }

        if (repeated > 3) {
            throw new Error("Too many failed requests");
        }

        repeated++;
        return this.put(url, data, headers, repeated);
    },
    async delete(
        url: string,
        headers: Record<string, any> = {},
        repeated = 0
    ): Promise<AxiosResponse<any>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.delete(process.env.API_BASE_URL + url, {
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`,
                },
            });

            if (response.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                }
                await this.refreshToken();

                repeated++;
                return this.delete(url, headers, repeated);
            }

            return response;
        } catch (err: any) {
            if (err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {}, status: 401 } as AxiosResponse<any>;
                }
                await this.refreshToken();

                repeated++;
                return this.delete(url, headers, repeated);
            }
        }

        if (repeated > 3) {
            throw new Error("Too many failed requests");
        }

        repeated++;
        return this.delete(url, headers, repeated);
    },
    async refreshToken(): Promise<void> {
        const authStore = useAuthorizationStore();
        let response: AxiosResponse<any> | false = false;
        if (authStore.getRefreshToken) {
            try {
                response = await axios.post(process.env.API_BASE_URL + "/token/refresh", {
                    refresh_token: authStore.getRefreshToken,
                });
            } catch (err: unknown) {
                await authStore.logout();
                return;
            }
            if (response && response.status === 200) {
                await authStore.authorize(
                    response.data.token,
                    response.data.refresh_token
                );
                return;
            }
        }
        await authStore.logout();
    },
}
