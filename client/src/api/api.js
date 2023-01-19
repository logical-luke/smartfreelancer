
import axios from "axios";
import store from "../store";


const getRequest = async function (url, headers) {
  const response = await axios.get(process.env.API_BASE_URL + url, {
    headers: {
      Authorization: `Bearer ${store.getters.getToken}`,
    },
  });

  if (response.status === 401) {
    await refreshToken();

    return getRequest(url, headers);
  }

  return response;
}

const postRequest =  async function (url, data, headers) {
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
}

const deleteRequest =  async function (url, data, headers) {
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
}

const refreshToken = async function () {
  const response = await axios.post(process.env.API_BASE_URL + '/token/refresh', {
    refresh_token: store.getters.getRefreshToken,
  });

  if (response.status === 200) {
      store.commit('setToken', response.data.token);
      store.commit('setRefreshToken', response.data.refresh_token);
  }
}

export default {
  async getProjects() {
      const response = await getRequest('/project/');

      return response.data;
  },

  async getProject(id) {
    const response = await getRequest('/project/' + id);

    return response.data;
  },

  async deleteProject(id) {
    const response = await deleteRequest('/project/delete/' + id);

    return response.data;
  },

  async login(email, password) {
    const response = await axios.post(process.env.API_BASE_URL + '/login', {
        email: email,
        password: password
    });

    if (response.status !== 200) {
        throw new Error(response.data.message);
    }

    return {
      token: response.data.token,
      refreshToken: response.data.refresh_token
    }
  },

  async updateProject(project) {
    const response = await postRequest('/project/update/' + project.id, project);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data
  },

  async createProject(project) {
    const response = await postRequest('/project/create', project);

    if (response.status !== 200) {
      throw new Error(response.data.message);
    }

    return response.data
  },

  async getUser() {
     const response = await getRequest('/user/');

      return response.data;
  }
};
