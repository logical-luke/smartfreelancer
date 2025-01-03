import axios, {AxiosError} from "axios";

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
        } catch (e: unknown) {
            if (e instanceof AxiosError) {
                if (e.response && e.response.status === 401) {
                    throw new Error("Invalid username or password");
                }
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
        } catch (e: unknown) {
            if (
                e instanceof AxiosError
                && e.response &&
                e.response.status === 400
            ) {
                throw new Error("Invalid request made.");
            }

            if (
                e instanceof AxiosError
                && e.response &&
                e.response.status === 409
            ) {
                throw new Error("Account with that email already exists");
            }
        }

        throw new Error("Unable to sign in. Please try again later.");
    },
}
