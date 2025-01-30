export default defineNuxtConfig({
  modules: ["@nuxtjs/tailwindcss"],

  tailwindcss: {
    configPath: "./tailwind.config.js",
    quiet: true, // Чтобы отключить предупреждение
  },

  compatibilityDate: "2025-01-31",
});