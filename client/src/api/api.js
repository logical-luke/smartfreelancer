import axios from "axios";
import store from "../store";

export default {
  async getProjects() {
    await wait(100);
    return axios.get("http://127.0.0.1:8000/api/project", {
      headers: {
        Authorization: `Bearer ${store.getters.getToken}`,
      },
    });
  },
};

function wait(ms) {
  return new Promise((resolve) => {
    setTimeout(resolve, ms);
  });
}
