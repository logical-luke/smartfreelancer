import axios from "axios";

axios.defaults.withCredentials = true;

export default {
    async login(email: string, password: string): Promise<{ token: string; refreshToken: string }> {
        try {
            const response = await axios.post(process.env.API_BASE_URL + "/login", {
                email: email,
                password: password,
            });

            if (response && response.status === 200) {
                return {
                    token: response.data.token,
                    refreshToken: response.data.refresh_token,
                };
            }
        } catch (e: any) {
            if (e.response && e.response.status === 401) {
                throw new Error("Invalid username or password");
            }
        }

        throw new Error("Unable to log in. Please try again later");
    },

    async register(email: string, password: string, confirmPassword: string): Promise<{ token: string; refreshToken: string }> {
        try {
            const response = await axios.post(
                process.env.API_BASE_URL + "/register",
                {
                    email: email,
                    password: password,
                    confirmPassword: confirmPassword,
                }
            );

            return {
                token: response.data.token,
                refreshToken: response.data.refresh_token,
            };
        } catch (e: any) {
            if (
                e.response &&
                (e.response.status === 400 || e.response.status === 409)
            ) {
                throw new Error("Invalid email or password");
            }
        }

        throw new Error("Unable to sign in. Please try again later.");
    },

    async postGoogleStart(): Promise<string> {
        const response = await axios.post(
            process.env.API_BASE_URL + "/google/connect"
        );

        return response.data.targetUrl;
    },

    async postGoogleCheck(payload: { code: string; state: string }): Promise<any> {
        const response = await axios.post(
            process.env.API_BASE_URL + "/google/connect/check",
            {
                code: payload.code,
                state: payload.state,
            }
        );

        return response.data;
    },
}
