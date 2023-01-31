import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import EnvironmentPlugin from "vite-plugin-environment";


function useCredentials() {
  return {
    name: 'use-credentials',
    transformIndexHtml(html) {
      return html.replace(
        'crossorigin',
        'crossorigin="anonymous"',
      );
    },
  };
}

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue(), vueJsx(), useCredentials(), EnvironmentPlugin(["API_BASE_URL", "NODE_ENV"])],
  assetsPublicPath: "/",
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
});
