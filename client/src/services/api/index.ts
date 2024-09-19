import axios, { AxiosResponse } from "axios";
import { useAuthorizationStore } from "@/stores/auth";

axios.defaults.withCredentials = true;

const getRequest = async function (
  url: string,
  params: Record<string, any> = {},
  headers: Record<string, any> = {},
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
      return { data: {} } as AxiosResponse<any>;
    }

    if (response.status === 401) {
      if (repeated > 0) {
        return await authStore.logout();
      }

      await refreshToken();

      repeated++;
      return getRequest(url, params, headers, repeated);
    }

    return response;
  } catch (err: any) {
    if (err.response?.status === 404) {
      return { data: {} } as AxiosResponse<any>;
    }

    if (err.response?.status === 401) {
      if (repeated > 0) {
        return await authStore.logout();
      }

      await refreshToken();

      repeated++;
      return getRequest(url, params, headers, repeated);
    }
  }

  if (repeated > 3) {
    throw new Error("Too many failed requests");
  }

  repeated++;
  return getRequest(url, params, headers, repeated);
};

const postRequest = async function (
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
        return await authStore.logout();
      }
      await refreshToken();

      repeated++;
      return postRequest(url, data, headers, repeated);
    }

    return response;
  } catch (err: any) {
    if (err.response?.status === 401) {
      if (repeated > 0) {
        return await authStore.logout();
      }
      await refreshToken();

      repeated++;
      return postRequest(url, data, headers, repeated);
    }
  }

  if (repeated > 3) {
    throw new Error("Too many failed requests");
  }

  repeated++;
  return postRequest(url, data, headers, repeated);
};

const putRequest = async function (
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
        return await authStore.logout();
      }
      await refreshToken();

      repeated++;
      return putRequest(url, data, headers, repeated);
    }

    return response;
  } catch (err: any) {
    if (err.response?.status === 401) {
      if (repeated > 0) {
        return await authStore.logout();
      }
      await refreshToken();

      repeated++;
      return putRequest(url, data, headers, repeated);
    }
  }

  if (repeated > 3) {
    throw new Error("Too many failed requests");
  }

  repeated++;
  return putRequest(url, data, headers, repeated);
};

const deleteRequest = async function (
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
      await refreshToken();

      repeated++;
      return deleteRequest(url, headers, repeated);
    }

    return response;
  } catch (err: any) {
    if (err.response?.status === 401) {
      if (repeated > 0) {
        return await authStore.logout();
      }
      await refreshToken();

      repeated++;
      return deleteRequest(url, headers, repeated);
    }
  }

  if (repeated > 3) {
    throw new Error("Too many failed requests");
  }

  repeated++;
  return deleteRequest(url, headers, repeated);
};

const refreshToken = async function (): Promise<void> {
  const authStore = useAuthorizationStore();
  let response: AxiosResponse<any> | false = false;
  if (authStore.getRefreshToken) {
    try {
      response = await axios.post(process.env.API_BASE_URL + "/token/refresh", {
        refresh_token: authStore.getRefreshToken,
      });
    } catch (err: any) {
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
};

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

  async deleteProject(id: string): Promise<any> {
    const response = await deleteRequest("/project/delete/" + id);

    return response.data;
  },

  async deleteProjects(ids: any): Promise<any> {
    const response = await deleteRequest("/project/delete", ids);

    return response.data;
  },

  async deleteTasks(ids: any): Promise<any> {
    const response = await deleteRequest("/task/delete", ids);

    return response.data;
  },

  async updateProject(project: any): Promise<any> {
    const response = await postRequest(
      "/project/update/" + project.id,
      project
    );

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async createProject(project: any): Promise<any> {
    const response = await postRequest("/project/create", project);
    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async deleteTask(id: string): Promise<any> {
    const response = await deleteRequest("/task/delete/" + id);

    return response.data;
  },

  async updateTask(task: any): Promise<any> {
    const response = await postRequest("/task/update/" + task.id, task);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async createTask(task: any): Promise<any> {
    const response = await postRequest("/task/create", task);
    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async getClient(id: string): Promise<any> {
    const response = await getRequest("/clients/" + id);

    return response.data;
  },

  async deleteClient(id: string): Promise<any> {
    const response = await deleteRequest("/clients/" + id);

    if (response.status !== 204) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async updateClient(client: any): Promise<any> {
    const response = await putRequest("/clients/" + client.id, client);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async createClient(client: any): Promise<any> {
    const response = await postRequest("/clients", client);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
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
  async getClients(): Promise<any> {
    const response = await getRequest("/clients");

    return response.data;
  },
};
