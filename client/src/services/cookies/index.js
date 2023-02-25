import VueCookies from "vue-cookies";

export default {
  async set(
    key,
    value,
    expires = "1d",
    path = "/",
    domain = null,
    secure = true,
    sameSite = "Strict"
  ) {
    return VueCookies.set(key, value, expires, path, domain, secure, sameSite);
  },
  async get(key) {
    return VueCookies.get(key);
  },
  async remove(key) {
    return VueCookies.remove(key);
  },
};
