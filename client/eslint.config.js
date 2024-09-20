import js from "@eslint/js";
import pluginVue from 'eslint-plugin-vue'
import eslintConfigPrettier from "eslint-config-prettier";
import globals from "globals";

export default [
    js.configs.recommended,
    ...pluginVue.configs['flat/recommended'],
    eslintConfigPrettier,
    {
        languageOptions: {
            globals: {
                ...globals.node
            }
        }
    },
    {
        ignores: ["node_modules/", "dist/", "public/", "tools/"],
    }
];
