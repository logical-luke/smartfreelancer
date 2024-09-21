import axios from "axios";
import type { AxiosResponse } from "axios";
import { useAuthorizationStore } from "@/stores/auth";

axios.defaults.withCredentials = true;

export default {
    async get<T = unknown>(
        url: string,
        params: Record<string, string> = {},
        headers: Record<string, string> = {},
        repeated = 0
    ): Promise<AxiosResponse<T>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.get<T>(process.env.API_BASE_URL + url, {
                params: params,
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`,
                },
            });

            if (response.status === 404) {
                return { data: {} as T, status: 404 } as AxiosResponse<T>;
            }

            if (response.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {} as T, status: 401 } as AxiosResponse<T>;
                }

                await this.refreshToken();

                repeated++;
                return this.get(url, params, headers, repeated);
            }

            return response;
        } catch (err: unknown) {
            if (axios.isAxiosError(err) && err.response?.status === 404) {
                return { data: {} as T, status: 404 } as AxiosResponse<T>;
            }

            if (axios.isAxiosError(err) && err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {} as T, status: 401 } as AxiosResponse<T>;
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
    async post<T = unknown>(
        url: string,
        data: Record<string, unknown> = {},
        headers: Record<string, string> = {},
        repeated = 0
    ): Promise<AxiosResponse<T>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.post<T>(process.env.API_BASE_URL + url, data, {
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`,
                },
            });

            if (response.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {} as T, status: 401 } as AxiosResponse<T>;
                }
                await this.refreshToken();

                repeated++;
                return this.post(url, data, headers, repeated);
            }

            return response;
        } catch (err: unknown) {
            if (axios.isAxiosError(err) && err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {} as T, status: 401 } as AxiosResponse<T>;
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
    async put<T = unknown>(
        url: string,
        data: Record<string, unknown> = {},
        headers: Record<string, string> = {},
        repeated = 0
    ): Promise<AxiosResponse<T>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.put<T>(process.env.API_BASE_URL + url, data, {
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`,
                },
            });

            if (response.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {} as T, status: 401 } as AxiosResponse<T>;
                }
                await this.refreshToken();

                repeated++;
                return this.put(url, data, headers, repeated);
            }

            return response;
        } catch (err: unknown) {
            if (axios.isAxiosError(err) && err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {} as T, status: 401 } as AxiosResponse<T>;
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
    async delete<T = unknown>(
        url: string,
        headers: Record<string, string> = {},
        repeated = 0
    ): Promise<AxiosResponse<T>> {
        const authStore = useAuthorizationStore();
        try {
            const response = await axios.delete<T>(process.env.API_BASE_URL + url, {
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
        } catch (err: unknown) {
            if (axios.isAxiosError(err) && err.response?.status === 401) {
                if (repeated > 0) {
                    await authStore.logout();
                    return { data: {} as T, status: 401 } as AxiosResponse<T>;
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
        if (authStore.getRefreshToken) {
            try {
                const response = await axios.post(process.env.API_BASE_URL + "/token/refresh", {
                    refresh_token: authStore.getRefreshToken,
                });

                if (response && response.status === 200) {
                    await authStore.authorize(
                        response.data.token,
                        response.data.refresh_token
                    );

                    return;
                }
            } catch (err: unknown) {
                if (err instanceof Error) {
                    console.error(err.message);
                }
                await authStore.logout();
                return;
            }
        }

        await authStore.logout();
    },
}
