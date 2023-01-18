
import axios from "axios";
import store from "../store";

export default {
  async getProjects() {
    return axios.get(process.env.API_BASE_URL + "/api/project", {
      headers: {
        Authorization: `Bearer ${store.getters.getToken}`,
      },
    });
  },
};
