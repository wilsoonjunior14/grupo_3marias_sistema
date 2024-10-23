const { defineConfig } = require("cypress");

module.exports = defineConfig({
  env: {
    baseUrl: "http://www.busqueiapp.com.br"
  },
  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    defaultCommandTimeout: 30000,
    retries: {
      runMode: 3,
      openMode: 3
    }
  },
});
