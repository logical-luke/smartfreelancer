import axios from "axios";
import store from "../store";

const getRequest = async function (url, headers) {
  try {
    const response = await axios.get(process.env.API_BASE_URL + url, {
      headers: {
        Authorization: `Bearer ${store.getters.getToken}`,
      },
    });

    if (response.status === 404) {
      return null;
    }

    return response;
  } catch (err) {
    if (err.response.status === 404) {
      return null;
    }

    if (err.response.status === 401) {
      await refreshToken();

      return getRequest(url, headers);
    }
  }

  return null;
};

const postRequest = async function (url, data, headers) {
  if (!data) {
    data = {};
  }
  const response = axios.post(process.env.API_BASE_URL + url, data, {
    headers: {
      Authorization: `Bearer ${store.getters.getToken}`,
    },
  });

  if (response.status === 401) {
    await refreshToken();

    return postRequest(url, data, headers);
  }

  return response;
};

const deleteRequest = async function (url, data, headers) {
  const response = axios.delete(process.env.API_BASE_URL + url, {
    headers: {
      Authorization: `Bearer ${store.getters.getToken}`,
    },
  });

  if (response.status === 401) {
    await refreshToken();

    return deleteRequest(url, data, headers);
  }

  return response;
};

const refreshToken = async function () {
  const response = await axios.post(
    process.env.API_BASE_URL + "/token/refresh",
    {
      refresh_token: store.getters.getRefreshToken,
    }
  );

  if (response.status === 200) {
    store.commit("setToken", response.data.token);
    store.commit("setRefreshToken", response.data.refresh_token);
  }
};

export default {
  async getProjects() {
    const response = await getRequest("/project/");

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
  },

  async login(email, password) {
    try {
      const response = await axios.post(process.env.API_BASE_URL + "/login", {
        email: email,
        password: password,
      });

      return {
        token: response.data.token,
        refreshToken: response.data.refresh_token,
      };
    } catch (e) {
      if (e.response.status === 401) {
        throw new Error("Invalid username or password.");
      }
    }

    throw new Error("Unable to log in. Please try again later.");
  },

  async getUser() {
    const response = await getRequest("/user/");

    return response.data;
  },


  async getTimer() {
    const response = await getRequest("/timer/");

    if (response) {
      return response.data;
    }

    return null;
  },

  async createTimer(timerPayload) {
    const response = await postRequest('/timer/create', timerPayload)

    return response.data;
  },

  async stopTimer(newTimer) {
    const response = await postRequest('/timer/stop')

    return response.data;
  },

  async getClients() {
    const response = await getRequest("/client/");

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
    const response = await postRequest(
      "/client/update/" + client.id,
      client
    );

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
  },
  async updateTimer(timer) {
    const response = await postRequest('/timer/update/' + timer.id, timer)

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }
  }
};
