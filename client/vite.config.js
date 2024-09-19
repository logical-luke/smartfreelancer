import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import EnvironmentPlugin from "vite-plugin-environment";

function useCredentials() {
  return {
    name: "use-credentials",
    transformIndexHtml(html) {
      return html.replace("crossorigin", 'crossorigin="anonymous"');
    },
  };
}

export default defineConfig({
  server: {
    port: 5173,
  },
  plugins: [
    vue(),
    vueJsx(),
    useCredentials(),
    EnvironmentPlugin(["API_BASE_URL", "NODE_ENV"]),
  ],
  assetsPublicPath: "/",
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  build: {
    rollupOptions: {
      output: {
        // replace feature flag globals with boolean literals
        manualChunks(id) {
          if (id.includes("vue-i18n")) {
            return "vue-i18n";
          }
        },
        globals: {
          "vue-i18n": "vue-i18n",
        },
      },
    },
  },
});
