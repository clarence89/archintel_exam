import path from "path";
import fs from "fs-extra";
const { exec } = require("child_process");

export default defineNuxtConfig({
  app: {
    baseURL: process.env.BASE_URL || "/",
    head: {
      link: [{ rel: "icon", type: "image/ico", href: process.env.BASE_URL + "/favicon.ico" }],
      title: process.env.APP_NAME,
    },
  },
  runtimeConfig: {
    app: {
      baseURL: process.env.BASE_URL || "/",
    },
    public: {
      apiUrl: process.env.API_URL,
      appName: process.env.APP_NAME,
    },
  },

  compatibilityDate: "2024-04-03",
  devtools: { enabled: true },
  modules: ["@nuxt/ui", "@nuxtjs/tailwindcss", "@nuxtjs/color-mode", "nuxt-tiptap-editor"],
  tiptap: {
    prefix: "Tiptap",
  },
  colorMode: {
    preference: "light",
    fallback: "light",
  },
  tailwindcss: {
    config: {
      theme: {
        extend: {
          colors: {
            primaryColor: process.env.PRIMARY_COLOR || "#00547c",
          },
        },
      },
    },
  },
});
