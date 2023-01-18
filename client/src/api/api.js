
import axios from "axios";
import store from "../store";


const get = async function (url, data, headers) {
  const response = await axios.get(process.env.API_BASE_URL + url, {
    headers: {
      Authorization: `Bearer ${store.getters.getToken}`,
    },
  });

  if (response.status === 401) {
    await refreshToken();

    return get(url, data, headers);
  }

  return response;
}

const post =  async function (url, data, headers) {
  const response = axios.post(process.env.API_BASE_URL + url, {
    headers: {
      Authorization: `Bearer ${store.getters.getToken}`,
    },
  });

  if (response.status === 401) {
    await refreshToken();

    return get(url, data, headers);
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
      return get('/project');
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
  }
};
