import axios from "axios";
import store from "../../store";
import authorization from "@/services/authorization";

axios.defaults.withCredentials = true;

const getRequest = async function (url, params, headers, repeated = 0) {
  try {
    const response = await axios.get(process.env.API_BASE_URL + url, {
      params: params,
      headers: {
        Authorization: `Bearer ${store.getters["authorization/getToken"]}`,
      },
    });

    if (response.status === 404) {
      return { data: {} };
    }

    if (response.status === 401) {
      if (repeated > 0) {
        return await authorization.logout();
      }

      await refreshToken();

      repeated++;
      return getRequest(url, params, headers, repeated);
    }

    return response;
  } catch (err) {
    if (err.response.status === 404) {
      return { data: {} };
    }

    if (err.response.status === 401) {
      if (repeated > 0) {
        return await authorization.logout();
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

const postRequest = async function (url, data, headers, repeated = 0) {
  if (!data) {
    data = {};
  }
  try {
    const response = axios.post(process.env.API_BASE_URL + url, data, {
      headers: {
        Authorization: `Bearer ${store.getters["authorization/getToken"]}`,
      },
    });

    if (response.status === 401) {
      if (repeated > 0) {
        return await authorization.logout();
      }
      await refreshToken();

      repeated++;
      return postRequest(url, data, headers, repeated);
    }

    return response;
  } catch (err) {
    if (err.response.status === 401) {
      if (repeated > 0) {
        return await authorization.logout();
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

const deleteRequest = async function (url, data, headers, repeated = 0) {
  if (!data) {
    data = {};
  }
  try {
    const response = axios.delete(process.env.API_BASE_URL + url, {
      data: data,
      headers: {
        Authorization: `Bearer ${store.getters["authorization/getToken"]}`,
      },
    });

    if (response.status === 401) {
      if (repeated > 0) {
        return await authorization.logout();
      }
      await refreshToken();

      repeated++;
      return deleteRequest(url, data, headers, repeated);
    }

    return response;
  } catch (err) {
    if (err.response.status === 401) {
      if (repeated > 0) {
        return await authorization.logout();
      }
      await refreshToken();

      repeated++;
      return deleteRequest(url, data, headers, repeated);
    }
  }

  if (repeated > 3) {
    throw new Error("Too many failed requests");
  }

  repeated++;
  return deleteRequest(url, data, headers, repeated);
};

const refreshToken = async function () {
  let response = false;
  if (store.getters["authorization/getRefreshToken"]) {
    try {
      response = await axios.post(process.env.API_BASE_URL + "/token/refresh", {
        refresh_token: store.getters["authorization/getRefreshToken"],
      });
    } catch (err) {
      await authorization.logout();

      return;
    }
    if (response && response.status === 200) {
      await authorization.authorize(response.data.token, response.data.refresh_token)

      return;
    }
  }
  await authorization.logout();
};

export default {
  async login(email, password) {
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
    } catch (e) {
      if (e.response && e.response.status === 401) {
        throw new Error("Invalid username or password");
      }
    }

    throw new Error("Unable to log in. Please try again later");
  },

  async register(email, password, confirmPassword) {
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
    } catch (e) {
      if (
        e.response &&
        (e.response.status === 400 || e.response.status === 409)
      ) {
        throw new Error(e.response.data.error);
      }
    }

    throw new Error("Unable to sign in. Please try again later.");
  },

  async getUser() {
    const response = await getRequest("/me");

    return response.data;
  },

  async getTimer() {
    const response = await getRequest("/timer");

    if (response) {
      return response.data;
    }

    return null;
  },

  async createTimer(timerPayload) {
    const response = await postRequest("/timer/create", timerPayload);

    return response.data;
  },

  async stopTimer(timer) {
    const response = await postRequest("/timer/stop", timer);

    return response.data;
  },

  async getProjects() {
    const response = await getRequest("/project");

    return response.data;
  },

  async getProject(id) {
    const response = await getRequest("/project/" + id);

    return response.data;
  },

  async deleteProject(id) {
    const response = await deleteRequest("/project/delete/" + id);

    return response.data;
  },

  async deleteClients(ids) {
    const response = await deleteRequest("/client/delete", ids);

    return response.data;
  },

  async deleteProjects(ids) {
    const response = await deleteRequest("/project/delete", ids);

    return response.data;
  },

  async deleteTasks(ids) {
    const response = await deleteRequest("/task/delete", ids);

    return response.data;
  },

  async updateProject(project) {
    const response = await postRequest(
      "/project/update/" + project.id,
      project
    );

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async createProject(project) {
    const response = await postRequest("/project/create", project);
    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async getTasks() {
    const response = await getRequest("/task");

    return response.data;
  },

  async getTask(id) {
    const response = await getRequest("/task/" + id);

    return response.data;
  },

  async deleteTask(id) {
    const response = await deleteRequest("/task/delete/" + id);

    return response.data;
  },

  async updateTask(task) {
    const response = await postRequest("/task/update/" + task.id, task);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async createTask(task) {
    const response = await postRequest("/task/create", task);
    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async getClients() {
    const response = await getRequest("/client");

    return response.data;
  },

  async getInitial() {
    const response = await getRequest("/synchronization");

    return response.data;
  },

  async getClient(id) {
    const response = await getRequest("/client/" + id);

    return response.data;
  },

  async deleteClient(id) {
    const response = await deleteRequest("/client/delete/" + id);

    return response.data;
  },

  async updateClient(client) {
    const response = await postRequest("/client/update/" + client.id, client);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },

  async createClient(client) {
    const response = await postRequest("/client/create", client);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data;
  },
  async updateTimer(timer) {
    const response = await postRequest("/timer/update/" + timer.id, timer);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }
  },

  async getTimeEntries(payload) {
    const response = await getRequest("/time-entry", payload);

    return response.data;
  },

  async postGoogleStart() {
    const response = await axios.post(
      process.env.API_BASE_URL + "/google/connect"
    );

    return response.data.targetUrl;
  },

  async postGoogleCheck(payload) {
    const response = await axios.post(
      process.env.API_BASE_URL + "/google/connect/check",
      {
        code: payload.code,
        state: payload.state,
      }
    );

    return response.data;
  },

  async pushSyncItem(payload) {
    const response = await postRequest("/synchronization/queue", payload);

    return response.data;
  },
};
